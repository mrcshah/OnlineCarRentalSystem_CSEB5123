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
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'transmission' => 'required|in:Automatic,Manual',
            'plate_number' => 'required|string|max:255|unique:cars,plate_number',
            'price_per_day' => 'required|numeric|min:0',
            'branch_id' => 'required|exists:branches,id',
        ]);

        Car::create($request->all());

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }
}
