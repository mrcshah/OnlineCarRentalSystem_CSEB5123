@extends('layouts.guest')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="bg-white p-4 rounded shadow w-100" style="max-width: 450px;">
        <h2 class="text-center fw-bold">Create a new account</h2>
        <p class="text-center text-muted mb-4">It's quick and easy.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row mb-3">
                <div class="col">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email address" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="New password" required>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Confirm password" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="" disabled selected>Select role</option>
                        <option value="customer">Customer</option>
                        <option value="staff">Staff</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 fw-bold">
                Sign Up
            </button>
        </form>
    </div>
</div>
@endsection
