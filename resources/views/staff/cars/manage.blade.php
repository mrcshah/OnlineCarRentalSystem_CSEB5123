@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Cars</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Car Creation Form -->
    <div class="card mb-4">
        <div class="card-header">Add New Car</div>
        <div class="card-body">
            <form action="{{ url('staff/cars') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select type="text" name="type" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="SUV">SUV</option>
                        <option value="Sedan">Sedan</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="MPV">MPV</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Coupe">Coupe</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Transmission</label>
                    <select name="transmission" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                    </select>
                </div>

                    <div class="mb-3">
                        <label>Price Per Day (RM)</label>
                        <input type="number" name="price_per_day" step="0.01" class="form-control" required>
                    </div>

                <div class="mb-3">
                    <label class="form-label">Plate Number</label>
                    <input type="text" name="plate_number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="car_image" class="form-label">Car Image</label>
                    <input type="file" name="car_image" id="car_image" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-control" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                               {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Add Car</button>
            </form>
        </div>
    </div>

    <!-- Car List -->
    <h4>Existing Cars</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Model</th>
                <th>Type</th>
                <th>Transmission</th>
                <th>Price Per Day (RM)</th>
                <th>Plate Number</th>
                <th>Car Image</th>
                <th>Branch</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cars as $car)
            <tr>
                <td>{{ $car->brand }}</td>
                <td>{{ $car->model }}</td>
                <td>{{ $car->type }}</td>
                <td>{{ $car->transmission }}</td>
                <td>{{ number_format($car->price_per_day, 2) }}</td>
                <td>{{ $car->plate_number }}</td>
                <td>
                    @if($car->car_image)
                        <img src="{{ asset('images/cars/' . $car->car_image) }}" alt="Car Image" width="100">
                    @else
                        No Image
                    @endif
                <td>{{ $car->branch->name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
