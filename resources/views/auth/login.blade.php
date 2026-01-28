<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form-SewaCare</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url("Images/background.jpeg") no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
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
<body class="d-flex justify-content-center align-items-center">

  <div class="login-card shadow-sm p-4 rounded-4">
    <img src="{{ asset('frontend/images/logo.png') }}" class="img-fluid d-block mx-auto logo-img" alt="SewaCare logo">
    <h3 class="text-center mb-3 text-info fw-semibold">Welcome Back</h3>
    <p class="text-center text-muted mb-4">Please login to your account</p>
    <form method="POST" action="{{ route('auth.doLogin') }}">
      @csrf
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
        <label for="email">Email address</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
        <label for="password">Password</label>
      </div>
      <div class="text-end mb-3">
        <a href="{{ route('auth.forgetpassword') }}" class="text-info text-decoration-none small">Forgot Password?</a>
      </div>
      <button type="submit" class="btn btn-info w-100 rounded-pill">Login</button>
    </form>

    <p class="mt-3 text-center small">
      Don't have an account? <a href="{{ route('auth.register') }}" class="text-info text-decoration-none">Register</a>
    </p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
