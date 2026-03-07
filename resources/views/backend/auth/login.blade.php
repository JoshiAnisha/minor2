@extends('backend.layouts.auth.app')

@section('title', 'Login - SewaCare')

@section('content')
    <div class="login-card shadow-sm p-4 rounded-4">
        <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" class="logo-img"></a>
        <h3 class="text-center mb-3 text-info fw-semibold">Welcome Back</h3>
        <p class="text-center text-muted mb-4">Please login to your account</p>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('backend.auth.login.post') }}">
            @csrf
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}"
                    required>
                <label>Email address</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <label>Password</label>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('backend.auth.password.request') }}" class="small text-info">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-info w-100 rounded-pill">Login</button>
        </form>

        <p class="mt-3 text-center small">
            Don't have an account? <a href="{{ route('backend.auth.register') }}" class="text-info">Register</a>
        </p>
    </div>
@endsection
