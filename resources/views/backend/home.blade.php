<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SewaCare - In-Home Medical Services</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .landing-hero { padding-top: 6rem; padding-bottom: 4rem; }
        .service-card-landing { border-radius: 16px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: transform 0.2s, box-shadow 0.2s; height: 100%; }
        .service-card-landing:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(14, 165, 233, 0.12); }
        .service-card-landing .card-body { padding: 1.5rem; }
        .section-services { padding: 4rem 0; }
    </style>
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
                        <a class="nav-link" href="{{ route('home') }}#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-info text-white ms-lg-3" href="{{ route('backend.auth.login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-info text-white ms-lg-3" href="{{ route('backend.auth.register') }}">Sign up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="landing-hero py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold display-5">Care Comes Home with SewaCare</h1>
                    <p class="lead text-muted">
                        Bringing professional healthcare and warm support right to your doorstep.
                    </p>
                    <a href="#services" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-heart-pulse me-2"></i> Explore Services
                    </a>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('images/HomePage.png') }}" class="img-fluid rounded shadow" alt="Care at home">
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="section-services bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Our Services</h2>
                <p class="text-muted">Explore care options. Sign up to book a service.</p>
            </div>

            @if(isset($services) && $services->isNotEmpty())
                <div class="row g-4">
                    @foreach($services as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="card service-card-landing h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title fw-bold mb-0">{{ $service->name }}</h5>
                                        <span class="badge bg-light text-dark">{{ ucfirst($service->service_type ?? 'regular') }}</span>
                                    </div>
                                    @if(!empty($service->details))
                                        <p class="text-muted small mb-3">{{ Str::limit($service->details, 90) }}</p>
                                    @endif
                                    <p class="fw-bold text-primary mb-3">Rs {{ number_format((float)($service->base_price ?? 0), 0) }}</p>
                                    <a href="{{ route('backend.auth.register') }}" class="btn btn-outline-primary btn-sm w-100">Sign up to book</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-heart-pulse text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">Services will be listed here soon.</p>
                    <a href="{{ route('backend.auth.register') }}" class="btn btn-primary btn-sm mt-3">Sign up</a>
                </div>
            @endif
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
