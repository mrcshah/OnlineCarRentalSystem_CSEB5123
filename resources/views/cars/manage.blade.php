<hr>

<h3 class="mt-4">Car List</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Plate</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Type</th>
            <th>Transmission</th>
            <th>Price (RM/day)</th>
            <th>Branch</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cars as $car)
        <tr>
            <td>{{ $car->plate_number }}</td>
            <td>{{ $car->brand }}</td>
            <td>{{ $car->model }}</td>
            <td>{{ $car->type }}</td>
            <td>{{ $car->transmission }}</td>
            <td>{{ $car->price_per_day }}</td>
            <td>{{ $car->branch->name ?? '-' }}</td>
            <td>
                <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('cars.destroy', $car->id) }}" method="POST" style="display:inline;" 
                      onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
