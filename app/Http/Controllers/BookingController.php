<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Branch;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with('cars', 'branch')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $branches = Branch::all();
        $cars = Car::with('branch')->get();

        $selectedCarId = $request->car_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $branchId = $request->branch_id;

        return view('bookings.create', compact('branches', 'cars', 'selectedCarId', 'startDate', 'endDate', 'branchId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = session('booking_data');
        if (!$data) {
            return redirect()->route('bookings.create')->with('error', 'No booking data found.');
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:'.Carbon::now()->addDays(2)->toDateString(),
            'end_date' => 'required|date|after_or_equal:start_date',
            'car_ids' => 'required|array|min:1|max:2',
            'car_ids.*' => 'exists:cars,id',
            'branch_id' => 'required|exists:branches,id'
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $carIds = $request->car_ids;

        // Rule 1: Check max 2 cars per same rental period for this user
        $overlappingBookings = Booking::where('user_id', Auth::id())
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function($query) use ($start, $end) {
                          $query->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                      });
            })
            ->withCount('cars')
            ->get();

        $totalBookedCars = $overlappingBookings->sum('cars_count');
        if ($totalBookedCars + count($carIds) > 2) {
            return back()->withErrors(['car_ids' => 'You can only book a maximum of 2 cars during the same rental period.']);
        }

        // Rule 2: Prevent double-booking the selected cars
        foreach ($carIds as $carId) {
            $conflict = DB::table('booking_car')
                ->join('bookings', 'booking_car.booking_id', '=', 'bookings.id')
                ->where('booking_car.car_id', $carId)
                ->where(function($query) use ($start, $end) {
                    $query->whereBetween('bookings.start_date', [$start, $end])
                          ->orWhereBetween('bookings.end_date', [$start, $end])
                          ->orWhere(function($query) use ($start, $end) {
                              $query->where('bookings.start_date', '<=', $start)
                                    ->where('bookings.end_date', '>=', $end);
                          });
                })
                ->exists();

            if ($conflict) {
                return back()->withErrors(['car_ids' => 'One or more selected cars are already booked in this date range.']);
            }
        }

        $branches = Car::whereIn('id', $carIds)->pluck('branch_id')->unique();

        if ($branches->count() > 1) {
            return back()->withErrors(['car_ids' => 'You can only book cars from the same branch.']);
        }

        $branchId = $branches->first();

        $days = $start->diffInDays($end) + 1; // Include the end date
        $totalPrice = Car::whereIn('id', $carIds)->sum('price_per_day') * $days;
        // Save booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'branch_id' => $branchId,
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'pending', // or whatever default you want
            'total_price' => $totalPrice,
        ]);
        // Attach cars
        $booking->cars()->attach($carIds);
        session()->forget('booking_data'); // clear session
        return redirect()->route('bookings.index')->with('success', 'Booking submitted!');
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'car_ids' => 'required|array|max:2',
            'car_ids.*' => 'exists:cars,id'
        ]);

        // Save data to session
        session([
            'booking_data' => $request->all()
        ]);

        return redirect()->route('bookings.confirm.view');
    }
    
    public function showConfirmation()
    {
        $data = session('booking_data');

        if (!$data) {
            return redirect()->route('bookings.create')->with('error', 'No booking data found.');
        }

        $cars = Car::whereIn('id', $data['car_ids'])->get();

        // Calculate total days and total price
        $start = \Carbon\Carbon::parse($data['start_date']);
        $end = \Carbon\Carbon::parse($data['end_date']);
        $days = $start->diffInDays($end) + 1;

        $total = $cars->sum(function ($car) use ($days) {
            return $car->price_per_day * $days;
        });

        return view('bookings.confirm', [
            'cars' => $cars,
            'data' => $data,
            'days' => $days,
            'total' => $total,
        ]);
    }
    
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
