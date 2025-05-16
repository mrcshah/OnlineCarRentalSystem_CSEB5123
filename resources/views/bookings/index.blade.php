@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Bookings</h2>

    <a href="{{ url('/bookings/create') }}" class="btn btn-success">+ Create New Booking</a>
    @if ($bookings->isEmpty())
        <p>No bookings yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Cars</th>
                    <th>Branch</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>
                            @foreach ($booking->cars as $car)
                                <li>{{ $car->brand }} - {{ $car->model }}</li>
                            @endforeach
                        </td>
                        <td>{{ $booking->branch->name ?? 'N/A' }}</td>
                        <td>{{ $booking->start_date }}</td>
                        <td>{{ $booking->end_date }}</td>
                        <td>{{ ucfirst($booking->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
