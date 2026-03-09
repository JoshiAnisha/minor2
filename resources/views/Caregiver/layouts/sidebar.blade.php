<style>
    .caregiver-sidebar {
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
        flex-shrink: 0;
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
    }
    .caregiver-sidebar::-webkit-scrollbar { display: none; }
    .caregiver-sidebar .sidebar-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .caregiver-sidebar .sidebar-brand .logo-img {
        height: 42px;
        width: auto;
        object-fit: contain;
        display: block;
    }
    .caregiver-sidebar .nav-link {
        color: #334155;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        margin: 0.25rem 1rem;
        transition: all 0.2s;
    }
    .caregiver-sidebar .nav-link:hover { background: #f1f5f9; color: #0ea5e9; }
    .caregiver-sidebar .nav-link.active { background: #e0f2fe; color: #0284c7; }
</style>
<aside class="caregiver-sidebar">
    <a href="{{ route('caregiver.dashboard') }}" class="sidebar-brand text-decoration-none">
        <img src="{{ asset('images/logo.png') }}" alt="Home" class="logo-img">
    </a>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('caregiver.dashboard') ? 'active' : '' }}" href="{{ route('caregiver.dashboard') }}">
            <i class="bi bi-house me-2"></i> Home
        </a>
        <a class="nav-link {{ request()->routeIs('caregiver.bookings') ? 'active' : '' }}" href="{{ route('caregiver.bookings') }}">
            <i class="bi bi-calendar-check me-2"></i> My Bookings
        </a>
        <a class="nav-link {{ request()->routeIs('caregiver.service.requests') ? 'active' : '' }}" href="{{ route('caregiver.service.requests') }}">
            <i class="bi bi-clipboard2-pulse me-2"></i> Service Requests
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
        <hr class="my-2 mx-3 border-secondary opacity-25">
        <form action="{{ route('backend.auth.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start" style="cursor:pointer"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
        </form>
    </nav>
</aside>
