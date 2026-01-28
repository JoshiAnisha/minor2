@extends('backend.layouts.auth.app')

@section('title', 'Register - SewaCare')

@section('content')
    <div class="register-card shadow-sm p-4 rounded-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="SewaCare Logo" class="logo-img">
        </a>
        <h3 class="text-center text-info fw-semibold mb-3">Create Your Account</h3>
        <p class="text-center text-muted mb-4">Join SewaCare for in-home medical support</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('backend.auth.register.post') }}">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="name" placeholder="Full Name"
                    value="{{ old('name') }}" required>
                <label>Full Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="your@example.com"
                    value="{{ old('email') }}" required>
                <label>Email Address</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" name="role" required>
                    <option value="">Choose your role</option>
                    <option value="caregiver" {{ old('role') == 'caregiver' ? 'selected' : '' }}>Caregiver</option>
                    <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                </select>
                <label>You are a...</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <label>Create Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password"
                    required>
                <label>Confirm Password</label>
            </div>

            <button type="submit" class="btn btn-info w-100 rounded-pill">Register</button>
        </form>

        <p class="text-center mt-3 small">
            Already have an account? <a href="{{ route('backend.auth.login') }}" class="text-info">Login</a>
        </p>
    </div>
@endsection
