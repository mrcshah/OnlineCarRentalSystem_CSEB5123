<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'transmission' => 'required|in:Automatic,Manual',
            'plate_number' => 'required|string|max:255|unique:cars,plate_number',
            'price_per_day' => 'required|numeric|min:0',
            'branch_id' => 'required|exists:branches,id',
            'car_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('car_image')) {
            $image = $request->file('car_image');
            $imageName = time() . '_' . $image->getClientOriginalExtension();
            $image->move(public_path('images/cars'), $imageName);
            $data['car_image'] = $imageName;
        }

        Car::create($data);

        return redirect()->back()->with('success', 'Car added successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    public function browse(Request $request)
    {
        $query = Car::with('branch');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('car_type')) {
            $query->where('type', $request->car_type); // assuming column name = 'type'
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

    // Optionally filter available by checking no booking conflict (skipped here)

        $cars = $query->get();
        $branches = Branch::all();
        $brands = Car::select('brand')->distinct()->pluck('brand');
        $types = Car::select('type')->distinct()->pluck('type');
        $transmissions = Car::select('transmission')->distinct()->pluck('transmission');

        return view('cars.browse', compact('cars', 'branches', 'brands', 'types', 'transmissions'));
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

        $data = $request->all();

        if($request->hasFile('car_image')){
            $image = $request->file('car_image');
            $imageName = time() . '_' . $image->getClientOriginalExtension();
            $image->move(public_path('images/cars'), $imageName);
            $data['car_image'] = $imageName;
        } else {
            unset($data['car_image']);
        }
        $car->update($data);

        return redirect()->route('cars.manage')->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.manage')->with('success', 'Car deleted successfully.');
    }
}
