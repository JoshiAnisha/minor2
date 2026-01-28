@extends('backend.layouts.auth.app')

@section('title', 'Forgot Password - SewaCare')

@section('content')
    <div class="forgot-card shadow-sm p-4 rounded-4">
        <img src="{{ asset('images/logo.png') }}" class="logo-img">
        <h4 class="text-center mb-3 text-info fw-semibold">Reset Your Password</h4>
        <p class="text-center text-muted mb-4">Enter your email to receive reset link</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('backend.auth.password.email') }}">
            @csrf
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}"
                    required>
                <label>Email address</label>
            </div>
            <button type="submit" class="btn btn-info w-100 rounded-pill">Send Reset Link</button>
        </form>

        <p class="mt-3 text-center small">
            Back to <a href="{{ route('backend.auth.login') }}" class="text-info">Login</a>
        </p>
    </div>
@endsection
