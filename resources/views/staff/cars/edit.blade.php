@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Car</h3>

    <form action="{{ route('cars.update', $car->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Brand</label>
            <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Model</label>
            <input type="text" name="model" value="{{ old('model', $car->model) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Type</label>
            <input type="text" name="type" value="{{ old('type', $car->type) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Transmission</label>
            <select name="transmission" class="form-control" required>
                <option value="Automatic" {{ $car->transmission == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                <option value="Manual" {{ $car->transmission == 'Manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Price Per Day (RM)</label>
            <input type="number" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day) }}" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Plate Number</label>
            <input type="text" name="plate_number" value="{{ old('plate_number', $car->plate_number) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Branch</label>
            <select name="branch_id" class="form-control" required>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $car->branch_id == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image:</label><br>
            @if($car->car_image)
                <img src="{{ asset('storage/' . $car->car_image) }}" width="150">
            @else
                <p>No image uploaded.</p>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Change Image</label>
            <input type="file" name="car_image" class="form-control">
        </div>

        <button class="btn btn-success">Update Car</button>
        <a href="{{ route('cars.manage') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
