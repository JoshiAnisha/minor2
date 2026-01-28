<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" >
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - SewaCare</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" >
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url("Images/background.jpeg") no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
    }

    .forgot-card {
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
<body class="d-flex justify-content-center align-items-center p-3">

  <div class="forgot-card shadow-sm p-4 rounded-4">
    <img src="{{ asset('frontend/images/logo.png') }}" class="img-fluid d-block mx-auto logo-img" alt="SewaCare logo">
    <h4 class="text-center mb-3 text-info fw-semibold">Reset Your Password</h4>
    <p class="text-center text-muted mb-4">Enter your email to receive reset link</p>

  <div class="form-floating mb-3">
    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
    <label for="email">Email address</label>
  </div>

  <button type="submit" class="btn btn-info w-100 rounded-pill">Send Reset Link</button>
</form>

      <div class="form-floating mb-3">
        <input type="number" class="form-control" id="number" placeholder="number" required>
        <label for="number">Enter PIN</label>
      </div>

      <button type="submit" class="btn btn-info w-100 rounded-pill">Reset </button>
    </form>

    <p class="mt-3 text-center small">
      Back to  <a href="{{ route('auth.login') }}" class="text-info fw-bold text-decoration-none">Login</a>
    </p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
