<style>
    .admin-sidebar {
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
    .admin-sidebar::-webkit-scrollbar { display: none; }
    .admin-sidebar .sidebar-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .admin-sidebar .sidebar-brand .logo-img {
        height: 42px;
        width: auto;
        object-fit: contain;
        display: block;
    }
    .admin-sidebar .nav-link {
        color: #334155;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        margin: 0.25rem 1rem;
        transition: all 0.2s;
    }
    .admin-sidebar .nav-link:hover { background: #f1f5f9; color: #0ea5e9; }
    .admin-sidebar .nav-link.active { background: #e0f2fe; color: #0284c7; }
</style>
<aside class="admin-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand text-decoration-none">
        <img src="{{ asset('images/logo.png') }}" alt="Home" class="logo-img">
    </a>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-house me-2"></i> Home
        </a>
        <a class="nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}" href="{{ route('admin.appointment') }}">
            <i class="bi bi-calendar-event me-2"></i> Appointments
        </a>
        <a class="nav-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
            <i class="bi bi-heart-pulse me-2"></i> Services
        </a>
        <a class="nav-link {{ request()->routeIs('admin.patient*') || request()->routeIs('admin.patients*') ? 'active' : '' }}" href="{{ route('admin.patient') }}">
            <i class="bi bi-people me-2"></i> Patient
        </a>
        <a class="nav-link {{ request()->routeIs('admin.caregiver*') || request()->routeIs('admin.caregivers*') ? 'active' : '' }}" href="{{ route('admin.caregiver') }}">
            <i class="bi bi-person-badge me-2"></i> Caregivers
        </a>
        <a class="nav-link {{ request()->routeIs('admin.feedback') ? 'active' : '' }}" href="{{ route('admin.feedback') }}">
            <i class="bi bi-chat-quote me-2"></i> Feedback
        </a>
        <hr class="my-2 mx-3 border-secondary opacity-25">
        <form action="{{ route('backend.auth.logout') }}" method="POST" class="m-0"
            onsubmit="return confirm('Are you sure you want to logout?')">
            @csrf
            <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start" style="cursor:pointer"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
        </form>
    </nav>
</aside>
