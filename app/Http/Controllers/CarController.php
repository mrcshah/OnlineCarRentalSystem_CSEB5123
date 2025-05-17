<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Branch;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage()
    {
        $cars = Car::with('branch')->get(); // get all cars and branch info
        $branches = Branch::all(); // for dropdown selection
        return view('staff.cars.manage', compact('cars','branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'transmission' => 'required|in:Automatic,Manual',
        'plate_number' => 'required|string|max:255|unique:cars,plate_number',
        'price_per_day' => 'required|numeric|min:0',
        'branch_id' => 'required|exists:branches,id',
        'car_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('car_image')) {
        $validated['car_image'] = $request->file('car_image')->store('images/cars', 'public');
    }

    Car::create($validated);

    return redirect()->back()->with('success', 'Car added successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $branches = Branch::all();
        return view('staff.cars.edit', compact('car', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'transmission' => 'required|in:Automatic,Manual',
            'plate_number' => 'required|string|max:255|unique:cars,plate_number,' . $car->id,
            'price_per_day' => 'required|numeric|min:0',
            'branch_id' => 'required|exists:branches,id',
            'car_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('car_image')) {
            if ($car->car_image && Storage::disk('public')->exists($car->car_image)) {
                Storage::disk('public')->delete($car->car_image);
            }

            $path = $request->file('car_image')->store('images/cars', 'public');
            $validate['car_image'] = $path;
        }

        $car->update($validate);

        return redirect()->back()->with('success', 'Car updated successfully.');
    }

    public function browse(Request $request)
    {
        $query = Car::where('is_available', true);
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        $cars = $query->with('branch')->get();
        return view('customer.cars.browse', compact('cars'));
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.manage')->with('success', 'Car deleted successfully.');
    }
}
