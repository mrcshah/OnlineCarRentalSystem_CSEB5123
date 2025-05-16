@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Welcome, {{ $user->name }}</h2>
    <p>Email: {{ $user->email }}</p>

    <hr>

    <h4>Your Bookings</h4>
    @if($bookings->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Cars</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>
                        <ul>
                            @foreach($booking->cars as $car)
                                <li>{{ $car->brand }} - {{ $car->model }} ({{ $car->plate_number }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $booking->start_date }}</td>
                    <td>{{ $booking->end_date }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>You have no bookings yet.</p>
    @endif
</div>
@endsection
