<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Home redirects to correct dashboard
Route::get('/home', function () {
    if (auth()->user()->role === 'staff') {
        return redirect('/dashboard/staff');
    } else {
        return redirect('/dashboard/customer');
    }
})->middleware('auth');

Route::middleware(['auth'])->group(function (){
    Route::get('/dashboard/customer', [CustomerController::class, 'index'])
    ->name('dashboard.customer')
    ->can('view-customer-dashboard');

    Route::get('/dashboard/staff', [StaffController::class, 'index'])
    ->name('dashboard.staff')
    ->can('view-staff-dashboard');

    Route::middleware('can:is-customer')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::get('/bookings/create', [BookingController::class, 'create']);
        Route::post('/bookings', [BookingController::class, 'store']);

        Route::get('/cars', [CarController::class, 'index']);
    });

    Route::middleware('can:is-staff')->group(function () {
        Route::get('/bookings/manage', [BookingController::class, 'manage']);
        Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus']);

        Route::get('staff/cars/manage', [CarController::class, 'manage']);
        Route::post('staff/cars', [CarController::class, 'store']);
        Route::put('staff/cars/{id}', [CarController::class, 'update']);
        Route::delete('staff/cars/{id}', [CarController::class, 'destroy']);
        
        Route::get('/staff/bookings', [StaffController::class, 'reviewBookings'])->name('staff.bookings.index');
        Route::patch('/staff/bookings/{booking}/approve', [StaffController::class, 'approveBooking'])->name('staff.bookings.approve');
        Route::patch('/staff/bookings/{booking}/reject', [StaffController::class, 'rejectBooking'])->name('staff.bookings.reject');

        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('/staff/branches/create', [BranchController::class, 'create'])->name('branches.create');
        Route::post('/staff/branches', [BranchController::class, 'store'])->name('branches.store');

    });
});