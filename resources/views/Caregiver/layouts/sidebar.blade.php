<style>
    .caregiver-sidebar {
        width: 260px;
        min-height: 100vh;
        background: linear-gradient(180deg, #0284c7 0%, #0ea5e9 50%, #38bdf8 100%);
        padding: 1.5rem 0;
    }
    .caregiver-sidebar .nav-link {
        color: rgba(255,255,255,0.9);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        margin: 0.25rem 1rem;
        transition: all 0.2s;
    }
    .caregiver-sidebar .nav-link:hover { background: rgba(255,255,255,0.2); color: #fff; }
    .caregiver-sidebar .nav-link.active { background: rgba(255,255,255,0.25); color: #fff; }
    .caregiver-sidebar .sidebar-brand { padding: 0 1.5rem 1rem; color: #fff; font-weight: 700; font-size: 1.25rem; }
</style>
<aside class="caregiver-sidebar">
    <a href="{{ route('caregiver.dashboard') }}" class="sidebar-brand text-decoration-none text-white d-block">SewaCare</a>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('caregiver.dashboard') ? 'active' : '' }}" href="{{ route('caregiver.dashboard') }}">
            <i class="bi bi-grid-1x2 me-2"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('caregiver.bookings') ? 'active' : '' }}" href="{{ route('caregiver.bookings') }}">
            <i class="bi bi-calendar-check me-2"></i> My Bookings
        </a>
        <a class="nav-link {{ request()->routeIs('caregiver.service.requests') ? 'active' : '' }}" href="{{ route('caregiver.service.requests') }}">
            <i class="bi bi-clipboard2-pulse me-2"></i> Service Requests
        </a>
        <a class="nav-link {{ request()->routeIs('caregiver.shift*') ? 'active' : '' }}" href="{{ route('caregiver.shift.index') }}">
            <i class="bi bi-clock me-2"></i> My Schedule
        </a>
        <a class="nav-link {{ request()->routeIs('caregiver.notifications*') ? 'active' : '' }}" href="{{ route('caregiver.notifications.index') }}">
            <i class="bi bi-bell me-2"></i> Notifications
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>
        <a class="nav-link {{ request()->routeIs('caregiver.profile*') ? 'active' : '' }}" href="{{ route('caregiver.profile.edit') }}">
            <i class="bi bi-person-circle me-2"></i> Profile
        </a>
        <hr class="my-2 mx-3 border-secondary opacity-50">
        <a class="nav-link text-white" href="{{ route('home') }}"><i class="bi bi-house me-2"></i> Home</a>
        <form action="{{ route('backend.auth.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start" style="cursor:pointer"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
        </form>
    </nav>
</aside>
