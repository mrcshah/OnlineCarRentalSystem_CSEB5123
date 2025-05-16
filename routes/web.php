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
    Route::get('/dashboard/customer', [CustomerController::class, 'dashboard'])
    ->name('customer.dashboard')
    ->can('view-customer-dashboard');

    Route::get('/dashboard/staff', [StaffController::class, 'dashboard'])
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

        Route::get('/cars/manage', [CarController::class, 'manage']);
        Route::post('/cars', [CarController::class, 'store']);
        Route::put('/cars/{id}', [CarController::class, 'update']);
        Route::delete('/cars/{id}', [CarController::class, 'destroy']);

        Route::get('/branches', [BranchController::class, 'index']);
    });
});