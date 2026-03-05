<style>
    .patient-sidebar {
        width: 260px;
        min-height: 100vh;
        position: fixed;
        background: linear-gradient(180deg, #0284c7 0%, #0ea5e9 50%, #38bdf8 100%);
        border: none;
    }
    .patient-sidebar .sidebar-brand { color: #fff; font-weight: 700; padding: 1rem 1.25rem; font-size: 1.25rem; }
    .patient-sidebar .nav-link { color: rgba(255,255,255,0.9); padding: 0.75rem 1.25rem; border-radius: 8px; margin: 0.25rem 0.75rem; }
    .patient-sidebar .nav-link:hover { background: rgba(255,255,255,0.2); color: #fff; }
    .patient-sidebar .nav-link.active { background: rgba(255,255,255,0.25); color: #fff; }
</style>
<div class="patient-sidebar">
    <a href="{{ route('patient.dashboard') }}" class="sidebar-brand text-decoration-none text-white d-block">SewaCare</a>
    <ul class="nav flex-column p-2">
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}" href="{{ route('patient.dashboard') }}">
                <i class="bi bi-house me-2"></i> Home
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.bookings*') ? 'active' : '' }}" href="{{ route('patient.bookings.index') }}">
                <i class="bi bi-calendar-check me-2"></i> Bookings
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link {{ request()->routeIs('patient.services*') ? 'active' : '' }}" href="{{ route('patient.services.index') }}">
                <i class="bi bi-heart-pulse me-2"></i> Services
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
        <hr class="my-2 mx-2 border-secondary opacity-50">
        <li class="nav-item">
            <form action="{{ route('backend.auth.logout') }}" method="POST" class="m-0 p-2">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start" style="cursor:pointer"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
            </form>
        </li>
    </ul>
</div>
