<?php

namespace App\Http\Controllers;

use Closure;
use App\Models\Staff;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    if (Auth::user()->role !== 'staff' && Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    return view('dashboard.staff');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        //
    }
    public function reviewBookings()
    {
        $bookings = Booking::with('user', 'cars')->latest()->get();
        return view('staff.bookings.index', compact('bookings'));
    }

    public function approveBooking(Booking $booking)
    {
        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');

        $carBranchIds = $booking->cars->pluck('branch_id')->unique();

        if ($carBranchIds->diff($userBranchIds)->isNotEmpty()) {
            return back()->withErrors(['unauthorized' => 'You can only approve bookings for your own branches.']);
        }

        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Booking approved successfully.');
    }

    public function rejectBooking(Booking $booking)
    {
        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');

        $carBranchIds = $booking->cars->pluck('branch_id')->unique();

        if ($carBranchIds->diff($userBranchIds)->isNotEmpty()) {
            return back()->withErrors(['unauthorized' => 'You can only reject bookings for your own branches.']);
        }

        $booking->update(['status' => 'rejected']);
        return back()->with('success', 'Booking rejected successfully.');
    }
}
