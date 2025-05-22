@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Browse Available Cars</h2>

    <form action="{{ route('cars.browse') }}" method="GET" class="mb-4 space-y-4">
        <div>
            <label for="branch_id">Branch:</label>
            <select name="branch_id" id="branch_id" class="form-select">
                <option value="">-- All Branches --</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="car_type">Car Type:</label>
            <select name="car_type" id="car_type" class="form-select">
                <option value="">-- All Types --</option>
                <option value="SUV" {{ request('car_type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                <option value="Sedan" {{ request('car_type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                <option value="Hatchback" {{ request('car_type') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
            </select>
        </div>

        <div>
            <label for="transmission">Transmission:</label>
            <select name="transmission" id="transmission" class="form-select">
                <option value="">-- All --</option>
                <option value="Automatic" {{ request('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                <option value="Manual" {{ request('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </div>

        <div>
            <label for="brand">Brand:</label>
            <select name="brand" id="brand" class="form-select">
                <option value="">-- All Brands --</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input" required>
        </div>

        <div>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input" required>
        </div>

        <button type="submit" class="btn btn-primary">Search</button>
    </form>


    <div class="row">
        @forelse ($cars as $car)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($car->car_image)
                        <img src="{{ asset('images/cars/' . $car->car_image) }}" class="card-img-top" alt="Car Image">
                    @else
                        No Image
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $car->model }}</h5>
                        <p class="card-text">
                            Branch: {{ $car->branch->name }} <br>
                            Price/Day: RM{{ $car->price_per_day }}
                        </p>
                        <a href="{{ route('bookings.create', ['car_id' => $car->id, 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'branch_id' => $car->branch_id]) }}" class="btn btn-success">Book Now</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No available cars found for your selected filters.</p>
        @endforelse
    </div>
</div>
@endsection