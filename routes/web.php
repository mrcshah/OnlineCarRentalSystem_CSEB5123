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
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::get('/bookings/confirm/view', [BookingController::class, 'showConfirmation'])->name('bookings.confirm.view');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

        Route::get('/cars', [CarController::class, 'index']);
        Route::get('/cars', [CarController::class, 'browse'])->name('cars.browse');
        Route::get('/cars/browse', [CarController::class, 'browse'])->name('cars.browse');

    });

    Route::middleware('can:is-staff')->group(function () {
        Route::get('/bookings/manage', [BookingController::class, 'manage']);
        Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus']);

        Route::get('staff/cars/manage', [CarController::class, 'manage'])->name('cars.manage');
        Route::post('staff/cars', [CarController::class, 'store'])->name('cars.store');
        Route::get('staff/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
        Route::put('staff/cars/{car}', [CarController::class, 'update'])->name('cars.update');
        Route::delete('staff/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
        
        Route::get('/staff/bookings', [StaffController::class, 'reviewBookings'])->name('staff.bookings.index');
        Route::patch('/staff/bookings/{booking}/approve', [StaffController::class, 'approveBooking'])->name('staff.bookings.approve');
        Route::patch('/staff/bookings/{booking}/reject', [StaffController::class, 'rejectBooking'])->name('staff.bookings.reject');

        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('/staff/branches/create', [BranchController::class, 'create'])->name('branches.create');
        Route::post('/staff/branches', [BranchController::class, 'store'])->name('branches.store');
        Route::post('/staff/branches/join', [BranchController::class, 'join'])->name('branches.join');
        Route::post('/staff/branches/{branch}/leave', [BranchController::class, 'leave'])->name('branches.leave');
        Route::post('/staff/branches/{branch}/kick/{user}', [BranchController::class, 'kick'])->name('branches.kick');
        Route::delete('/staff/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');
        Route::get('/staff/branches/{branch}/manage', [BranchController::class, 'manage'])->name('branches.manage');

    });
});