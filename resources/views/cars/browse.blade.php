@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Browse Available Cars</h3>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('cars.browse') }}" class="row mb-4">
        <div class="col-md-2">
            <input type="text" name="brand" class="form-control" placeholder="Brand" value="{{ request('brand') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="type" class="form-control" placeholder="Type" value="{{ request('type') }}">
        </div>
        <div class="col-md-2">
            <select name="transmission" class="form-control">
                <option value="">Transmission</option>
                <option value="Automatic" {{ request('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                <option value="Manual" {{ request('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Car Listing -->
    <div class="row">
        @forelse($cars as $car)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($car->car_image)
                    <img src="{{ asset('storage/' . $car->car_image) }}" class="card-img-top" alt="{{ $car->brand }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $car->brand }} {{ $car->model }}</h5>
                    <p class="card-text">
                        Type: {{ $car->type }}<br>
                        Transmission: {{ $car->transmission }}<br>
                        Price: RM{{ number_format($car->price_per_day, 2) }} / day<br>
                        Branch: {{ $car->branch->name ?? '-' }}
                    </p>
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                        <button type="submit" class="btn btn-success w-100">Book Now</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
            <p class="text-muted">No cars found matching the criteria.</p>
        @endforelse
    </div>
</div>
@endsection
