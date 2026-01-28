<style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      background-color: white;
      height: 100vh;
      border-right: 1px solid #dee2e6;
    }

    .sidebar .nav-link {
      color: #333;
      padding: 12px;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #e9f7fc;
      color: #0dcaf0;
    }
    .card {
      border-left: 5px solid #0dcaf0;
    }

    .logo-img {
          max-height: 50px;
    }
  </style>
 <div class="col-md-3 col-lg-2 sidebar p-3">
      <div class="mb-4 text-start">
        <a href="caregiver.html">
          <img src="{{ asset('frontend/images/logo.png') }}" alt="SewaCare Logo" class="img-fluid logo-img">
        </a>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('caregiver.dashboard') }}">Dashboard</a>
        <a class="nav-link" href="{{ route('caregiver.bookings') }}">My Bookings</a>
        <a class="nav-link" href="{{ route('caregiver.shift-time') }}">My Schedule</a>
        <a class="nav-link" href="{{ route('Caregiver.serviceRequest') }}">My Services</a>
        <a class="nav-link" href="{{ route('Caregiver.edit') }}">My Profile</a>
        <a class="nav-link text-danger" href="{{ route('logout') }}">Logout</a>
      </nav>
    </div>
