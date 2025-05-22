@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Confirm Booking</h2>
    @php
        $branch = \App\Models\Branch::find($data['branch_id']);
    @endphp
    <p><strong>Branch:</strong> {{ $branch ? $branch->name : 'Unknown' }}</p>
    <p><strong>Rental Period:</strong> {{ $data['start_date'] }} to {{ $data['end_date'] }} ({{ $days }} day{{ $days > 1 ? 's' : '' }})</p>

    <h4>Selected Cars:</h4>
    @foreach ($cars as $car)
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>{{ $car->brand }} {{ $car->model }}</strong></p>
                @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}" alt="Car Image" class="mt-2" style="max-width: 300px;" class="img-thumbnail mb-2">
                @endif
                <p>Plate Number: {{ $car->plate_number }}</p>
                <p>Transmission: {{ $car->transmission }}</p>
                <p>Price per day: RM {{ number_format($car->price_per_day, 2) }}</p>
                <p>Total: RM {{ number_format($car->price_per_day * $days, 2) }}</p>
            </div>
        </div>
    @endforeach

    <h4>Total Price: RM {{ number_format($total, 2) }}</h4>

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        <input type="hidden" name="start_date" value="{{ $data['start_date'] }}">
        <input type="hidden" name="end_date" value="{{ $data['end_date'] }}">
        <input type="hidden" name="branch_id" value="{{ $data['branch_id'] }}">
        @foreach ($data['car_ids'] as $carId)
            <input type="hidden" name="car_ids[]" value="{{ $carId }}">
        @endforeach
        <button type="submit" class="btn btn-success">Submit Booking</button>
    </form>

</div>
@endsection
