<nav class="navbar" id="mainNavbar">
    <div class="container navbar-wrapper">

        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('images/aura-studio-logo.png') }}" alt="Aura Studio" class="navbar-logo">
        </a>

        <button class="navbar-toggle" id="navbarToggle" aria-label="Buka menu">
            <i class="bi bi-list"></i>
        </button>

        <div class="navbar-menu" id="navbarMenu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                Beranda
            </a>

            <a href="#">Layanan</a>
            <a href="#">Paket</a>
            <a href="#">Cabang</a>
            <a href="#">Fotografer</a>
            <a href="#">Galeri</a>
            <a href="#">Tentang Kami</a>
        </div>

        <div class="navbar-actions">
            <a href="#" class="btn btn-ghost">
                <i class="bi bi-person"></i>
                Login
            </a>

            <a href="#" class="btn btn-primary">
                Booking
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </div>
</nav>