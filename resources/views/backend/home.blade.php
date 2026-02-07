<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SewaCare - In-Home Medical Services</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top border-bottom border-info">
        <div class="container">
            <a class="navbar-brand fw-bold text-info" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" class="logo-img">
            </a>

            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.services.index') }}">Services</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-info text-white ms-lg-3" href="{{ route('backend.auth.login') }}">
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-info text-white ms-lg-3" href="{{ route('backend.auth.register') }}">
                            Signup
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5 mt-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold display-5">Care Comes Home with SewaCare</h1>
                    <p class="lead text-muted">
                        Bringing professional healthcare and warm support right to your doorstep.
                    </p>
                    <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-lg mt-3">
                        Explore Services
                    </a>
                </div>

                <div class="col-md-6">
                    <img src="{{ asset('images/HomePage.png') }}" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
