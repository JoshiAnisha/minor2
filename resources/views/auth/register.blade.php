<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" >
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url("Images/background.jpeg") no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
    }

    .register-card {
      width: 100%;
      max-width: 500px;
      background-color: #ffffffd9;
      backdrop-filter: blur(5px);
    }

    .logo-img {
      display: block;
      margin: 0 auto 20px;
      max-width: 150px;
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center p-3">

  <div class="register-card shadow-sm p-4 rounded-4">
    <img src="{{ asset('frontend/images/logo.png') }}" class="img-fluid d-block mx-auto logo-img" alt="SewaCare logo">
    <h3 class="text-center text-info fw-semibold mb-3">Create Your Account</h3>
    <p class="text-center text-muted mb-4">Join SewaCare for in-home medical support</p>

    <form action="{{ route('auth.register') }}" method="POST">
      @csrf

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="fullname" name="name" placeholder="Full Name" required>
        <label for="fullname">Full Name</label>
      </div>

      <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" name="email" placeholder="your@example.com" required>
        <label for="email">Email Address</label>
      </div>

      <div class="form-floating mb-3">
        <select class="form-select" id="role" name="role" required>
          <option value="">Choose your role</option>
          <option value="caregiver">Caregiver</option>
          <option value="patient">Patient</option>
        </select>
        <label for="role">You are a...</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        <label for="password">Create Password</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
        <label for="password_confirmation">Confirm Password</label>
      </div>

      <button type="submit" class="btn btn-info w-100 rounded-pill">Register</button>
    </form>

    <p class="text-center mt-3 small">
      Already have an account?
      <a href="{{ route('auth.login') }}" class="text-info fw-bold text-decoration-none">Login</a>
    </p>

    @if($errors->any())
      <div class="alert alert-danger mt-3">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
