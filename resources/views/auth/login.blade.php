@extends('layouts.guest')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="row w-100 mx-4">
        <!-- Left Section -->
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <h1 class="display-5 text-dark fw-bold">ECE Car Rental</h1>
            <p class="fs-5 text-muted">
                Book your ride quickly and easily with our online rental system.
                Whether you're staff or customer, we’ve got the right tools for your journey.
            </p>
        </div>

        <!-- Right Section -->
        <div class="col-md-6 d-flex justify-content-center">
            <div class="bg-white p-4 rounded shadow-sm w-100" style="max-width: 400px;">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autofocus
                               placeholder="Email address or phone number">

                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required
                               placeholder="Password">

                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        Log in
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                Forgotten password?
                            </a>
                        </div>
                    @endif

                    @if (Route::has('register'))
                        <hr>
                        <a href="{{ route('register') }}" class="btn btn-success w-100">
                            Create new account
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
