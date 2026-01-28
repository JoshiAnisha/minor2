<div class="sidebar bg-white border-end vh-100 position-fixed" style="width: 260px;">
    <div class="sidebar-header p-3">
        <h5 class="fw-bold">SewaCare</h5>
    </div>
    <ul class="nav flex-column p-2">
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="{{ route('home') }}">
                <i class="bi bi-house me-2"></i> Home
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="{{ route('patient.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="{{ route('patient.bookings.index') }}">
                <i class="bi bi-calendar-check me-2"></i> Bookings
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="{{ route('patient.services.index') }}">
                <i class="bi bi-heart-pulse me-2"></i> Services
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="{{ route('patient.invoices.index') }}">
                <i class="bi bi-receipt me-2"></i> Invoices
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="{{ route('patient.profile.show') }}">
                <i class="bi bi-person-circle me-2"></i> Profile
            </a>
        </li>
    </ul>
</div>
