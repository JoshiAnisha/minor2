<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SewaCare</title>

    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f9ff; color: #1e293b; }
        .main-content { margin-left: 260px; padding: 2rem; min-height: 100vh; }
        .btn-primary, .btn-info { background-color: #0ea5e9; border-color: #0ea5e9; border-radius: 10px; font-weight: 500; }
        .btn-primary:hover, .btn-info:hover { background-color: #0284c7; border-color: #0284c7; }
        .card { border-radius: 16px; border: none; }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    @include('patient.layouts.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
