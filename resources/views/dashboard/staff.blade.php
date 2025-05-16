@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Staff Dashboard</h1>

    <div class="card mt-4">
        <div class="card-body">
            <p>Welcome, {{ Auth::user()->name }}!</p>
            <p>This is the staff dashboard where you can manage cars, view bookings, and more.</p>
        </div>
    </div>
</div>
@endsection
