@extends('layouts.app')

@section('content')
<div class="container">
<h2>Manage Branch: {{ $branch->name }}</h2>

<p>Location: {{ $branch->location }}</p>
<p>Join Code: {{ $branch->code }}</p>
<p>Created by: {{ $branch->owner?->id === auth()->id() ? 'You' : ($branch->owner?->name ?? 'Unknown') }}</p>

<h3>Staff List</h3>
<ul>
    @foreach ($branch->staff as $user)
        <li>
            {{ $user->name }}
            @if ($branch->owner_id === auth()->id() && $user->id !== auth()->id())
                <form method="POST" action="{{ route('branches.kick', [$branch, $user]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Kick</button>
                </form>
            @endif
        </li>
    @endforeach
</ul>

@if ($branch->owner_id === auth()->id())
    <form method="POST" action="{{ route('branches.destroy', $branch) }}">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Are you sure you want to delete this branch?')">Delete Branch</button>
    </form>
@else
    <form method="POST" action="{{ route('branches.leave', $branch) }}">
        @csrf
        <button type="submit">Leave Branch</button>
    </form>
@endif
</div>
@endsection
