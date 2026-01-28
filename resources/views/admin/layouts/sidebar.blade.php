<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    .sidebar {
        height: 100vh;
        background-color: #ffffff;
        border-right: 1px solid #dee2e6;
    }

    .sidebar .nav-link {
        color: #333;
        padding: 12px;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background-color: #e9f7fc;
        color: #0dcaf0;
    }
</style>
<div class="col-lg-2 col-md-3 sidebar p-3">
    <div class="text-left mb-4">
        <a href="admin.html">
            <img src="{{ asset('images/logo.png') }}" alt="SewaCare Logo" class="img-fluid logo-img">
        </a>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a class="nav-link" href="{{ route('admin.appointment') }}">Appointments</a>
        <a class="nav-link" href="{{ route('admin.patient') }}">Patient</a>
        <a class="nav-link" href="{{ route('admin.caregiver') }}">Caregivers</a>
        <a class="nav-link" href="{{ route('admin.feedback') }}">Feedback</a>
        <form action="{{ route('logout') }}" method="POST"
            onsubmit="return confirm('Are you sure you want to logout?')">

            @csrf
            <button type="submit" class="nav-link text-danger btn btn-link p-0 text-start">
                Logout
            </button>
        </form>
    </nav>
</div>
