@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Branches</h2>
    <a href="{{ route('branches.create') }}" class="btn btn-primary mb-3">Create New Branch</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
                <tr>
                    <td>{{ $branch->name }}</td>
                    <td>{{ $branch->location }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
