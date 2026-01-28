<style>
      body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: white;
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
    .logo-img {
      max-height: 50px;
    }
</style>
<div class="col-md-3 col-lg-2 sidebar p-3">
      <div class="mb-4 text-start">
        <a href="user.html">
          <img src="{{ asset('frontend/images/logo.png') }}" alt="SewaCare Logo" class="img-fluid logo-img">
        </a>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('patient.dashboard') }}">Dashboard</a>
        <a class="nav-link" href="../appointments.html">My Appointments</a>
        <a class="nav-link" href="../booking.html">Booking</a>
        <a class="nav-link" href="../patientprofile.html">My Profile</a>
        <a class="nav-link" href="../service.html">Services</a>
        <a class="nav-link" href="../invoice.html">Invoices</a>
        <a class="nav-link text-danger" href="#">Logout</a>
      </nav>
    </div>
