<nav class="navbar navbar-expand-lg bg-light border-bottom border-info">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">SewaCare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('book') }}">Book Now</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('faq') }}">FAQ</a></li>
            </ul>
            <form class="d-flex">
                <button class="btn btn-outline-info" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
