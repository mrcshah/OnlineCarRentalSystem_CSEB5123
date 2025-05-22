@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Booking</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/bookings') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="branch_id" class="form-label">Select Branch</label>
            <select name="branch_id" id="branch_id" class="form-control" required>
                <option value="">-- Select Branch --</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ old('branch_id', $prefill['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Rental Start Date</label>
            <input type="date" name="start_date" class="form-control" required
                value="{{ old('start_date', $prefill['start_date'] ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Rental End Date</label>
            <input type="date" name="end_date" class="form-control" required
                value="{{ old('end_date', $prefill['end_date'] ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Select Car(s) (Max 2)</label>
            <div id="car-list">
                @foreach ($cars as $car)
                    <div class="form-check car-option" data-branch="{{ $car->branch_id }}">
                        <input class="form-check-input car-checkbox" type="checkbox" name="car_ids[]" value="{{ $car->id }}" id="car{{ $car->id }}"
                            {{ (old('car_ids') && in_array($car->id, old('car_ids'))) || (isset($selectedCarId) && $selectedCarId == $car->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="car{{ $car->id }}">
                            {{ $car->brand }} - {{ $car->model }} ({{ $car->transmission }}) - {{ $car->branch->name }}<br> RM {{ $car->price_per_day }} / day
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit Booking</button>
    </form>
</div>

<script>
    const branchSelect = document.getElementById('branch_id');
    const carOptions = document.querySelectorAll('.car-option');
    const checkboxes = document.querySelectorAll('.car-checkbox');

    branchSelect.addEventListener('change', () => {
        const selectedBranch = branchSelect.value;
        carOptions.forEach(option => {
            const match = option.dataset.branch === selectedBranch;
            option.style.display = match ? 'block' : 'none';
        });
    });

    checkboxes.forEach(box => {
        box.addEventListener('change', () => {
            const selected = document.querySelectorAll('.car-checkbox:checked');
            if (selected.length > 2) {
                alert('You can only select a maximum of 2 cars.');
                box.checked = false;
            }
        });
    });
</script>
@endsection