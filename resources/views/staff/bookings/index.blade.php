@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Booking Review Panel</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Message if user is not in any branch --}}
    @if(auth()->user()->branches->isEmpty())
        <div class="alert alert-warning">
            You have not joined any branch yet. Please join a branch to approve or reject bookings.
        </div>
    @endif

    @php $hasVisibleBooking = false; @endphp

    @foreach($bookings as $booking)
        @php
            $carBranchIds = $booking->cars->pluck('branch_id')->unique();
            $userBranchIds = auth()->user()->branches->pluck('id');
            $canApprove = $carBranchIds->diff($userBranchIds)->isEmpty();
        @endphp

        @if ($canApprove)
            @php $hasVisibleBooking = true; @endphp
            <div class="card mb-3">
                <div class="card-header">
                    Booking by {{ $booking->user->name }} ({{ $booking->user->email }})
                    <span class="badge bg-secondary float-end">{{ ucfirst($booking->status) }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Start:</strong> {{ $booking->start_date }}</p>
                    <p><strong>End:</strong> {{ $booking->end_date }}</p>
                    <p><strong>Cars:</strong></p>
                    <ul>
                        @foreach($booking->cars as $car)
                            <li>{{ $car->brand }} - {{ $car->model }} ({{ $car->plate_number }})</li>
                        @endforeach
                    </ul>

                    @if ($booking->status === 'pending')
                        <form method="POST" action="{{ route('staff.bookings.approve', $booking) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-success btn-sm">Approve</button>
                        </form>

                        <form method="POST" action="{{ route('staff.bookings.reject', $booking) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    @endforeach

    {{-- Message if no bookings shown for staff's branch --}}
    @if(!$hasVisibleBooking && auth()->user()->branches->isNotEmpty())
        <div class="alert alert-info">
            There are currently no bookings available for you to approve or reject.
        </div>
    @endif
</div>
@endsection
