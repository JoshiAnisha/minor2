@extends('backend.layouts.auth.app')

@section('title', 'Reset Password - SewaCare')

@section('content')
    <div class="reset-card shadow-sm p-4 rounded-4">
        <img src="{{ asset('images/logo.png') }}" class="logo-img">
        <h4 class="text-center mb-3 text-info fw-semibold">Create New Password</h4>
        <p class="text-center text-muted mb-4">Set a new password to access your account</p>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('backend.auth.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" placeholder="New Password" required>
                <label>New Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password"
                    required>
                <label>Confirm Password</label>
            </div>

            <button type="submit" class="btn btn-info w-100 rounded-pill">Reset Password</button>
        </form>

        <p class="mt-3 text-center small">
            Back to <a href="{{ route('backend.auth.login') }}" class="text-info">Login</a>
        </p>
    </div>
@endsection
