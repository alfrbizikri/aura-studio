<nav class="navbar" id="mainNavbar" aria-label="Navigasi utama">
    <div class="container navbar-wrapper">

        <a href="{{ route('home') }}" class="navbar-brand" aria-label="Aura Studio — Beranda">
            <img src="{{ asset('images/aura-studio-logo.png') }}" alt="Aura Studio" class="navbar-logo">
        </a>

        <button
            type="button"
            class="navbar-toggle"
            id="navbarToggle"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="navbarMenu">
            <i class="bi bi-list"></i>
        </button>

        <div class="navbar-menu" id="navbarMenu">
            <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'active' : '' }}"
                @if (request()->routeIs('home')) aria-current="page" @endif>
                Beranda
            </a>

            <a href="{{ route('services.index') }}"
                class="{{ request()->routeIs('services.*') ? 'active' : '' }}"
                @if (request()->routeIs('services.*')) aria-current="page" @endif>
                Layanan
            </a>

            <a href="{{ route('packages.index') }}"
                class="{{ request()->routeIs('packages.*') ? 'active' : '' }}"
                @if (request()->routeIs('packages.*')) aria-current="page" @endif>
                Paket
            </a>

            <<a href="{{ route('branches.index') }}"
                class="navbar-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                Cabang
                </a>
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