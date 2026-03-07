<style>
    .patient-sidebar {
        width: 260px;
        min-height: 100vh;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1030;
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
    }
    .patient-sidebar::-webkit-scrollbar { display: none; }
    .patient-sidebar .sidebar-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .patient-sidebar .sidebar-brand .logo-img {
        height: 42px;
        width: auto;
        object-fit: contain;
        display: block;
    }
    .patient-sidebar .nav-link { color: #334155; padding: 0.75rem 1.25rem; border-radius: 8px; margin: 0.25rem 0.75rem; }
    .patient-sidebar .nav-link:hover { background: #f1f5f9; color: #0ea5e9; }
    .patient-sidebar .nav-link.active { background: #e0f2fe; color: #0284c7; }
</style>
<div class="patient-sidebar">
    <a href="{{ route('patient.dashboard') }}" class="sidebar-brand text-decoration-none">
        <img src="{{ asset('images/logo.png') }}" alt="Home" class="logo-img">
    </a>
    <ul class="nav flex-column p-2">
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}" href="{{ route('patient.dashboard') }}">
                <i class="bi bi-house me-2"></i> Home
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.services*') ? 'active' : '' }}" href="{{ route('patient.services.index') }}">
                <i class="bi bi-heart-pulse me-2"></i> Services
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.bookings*') ? 'active' : '' }}" href="{{ route('patient.bookings.index') }}">
                <i class="bi bi-calendar-check me-2"></i> Bookings
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.service-requests*') ? 'active' : '' }}" href="{{ route('patient.service-requests.index') }}">
                <i class="bi bi-clipboard2-pulse me-2"></i> My Requests
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.invoices*') ? 'active' : '' }}" href="{{ route('patient.invoices.index') }}">
                <i class="bi bi-receipt me-2"></i> Invoices
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.notifications*') ? 'active' : '' }}" href="{{ route('patient.notifications.index') }}">
                <i class="bi bi-bell me-2"></i> Notifications
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.profile*') ? 'active' : '' }}" href="{{ route('patient.profile.show') }}">
                <i class="bi bi-person-circle me-2"></i> Profile
            </a>
        </li>
        <hr class="my-2 mx-2 border-secondary opacity-25">
        <li class="nav-item">
            <form action="{{ route('backend.auth.logout') }}" method="POST" class="m-0 p-2">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start" style="cursor:pointer"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
            </form>
        </li>
    </ul>
</div>
