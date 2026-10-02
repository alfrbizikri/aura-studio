<nav class="navbar" id="mainNavbar" aria-label="Navigasi utama">

    <div class="container navbar-wrapper">

        {{-- LOGO --}}
        <a
            href="{{ route('home') }}"
            class="navbar-brand"
            aria-label="Aura Studio — Beranda">
            <img
                src="{{ asset('images/aura-studio-logo.png') }}"
                alt="Aura Studio"
                class="navbar-logo">
        </a>


        {{-- MOBILE TOGGLE --}}
        <button
            type="button"
            class="navbar-toggle"
            id="navbarToggle"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="navbarMenu">
            <i class="bi bi-list"></i>
        </button>


        {{-- MENU --}}
        <div class="navbar-menu" id="navbarMenu">

            <a
                href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'active' : '' }}"
                @if (request()->routeIs('home'))
                aria-current="page"
                @endif
                >
                Beranda
            </a>


            <a
                href="{{ route('services.index') }}"
                class="{{ request()->routeIs('services.*') ? 'active' : '' }}"
                @if (request()->routeIs('services.*'))
                aria-current="page"
                @endif
                >
                Layanan
            </a>


            <a
                href="{{ route('packages.index') }}"
                class="{{ request()->routeIs('packages.*') ? 'active' : '' }}"
                @if (request()->routeIs('packages.*'))
                aria-current="page"
                @endif
                >
                Paket
            </a>


            <a
                href="{{ route('branches.index') }}"
                class="{{ request()->routeIs('branches.*') ? 'active' : '' }}"
                @if (request()->routeIs('branches.*'))
                aria-current="page"
                @endif
                >
                Cabang
            </a>


            <a
                href="{{ route('photographers.index') }}"
                class="{{ request()->routeIs('photographers.*') ? 'active' : '' }}"
                @if (request()->routeIs('photographers.*'))
                aria-current="page"
                @endif
                >
                Fotografer
            </a>


            <a
                href="{{ route('gallery.index') }}"
                class="{{ request()->routeIs('gallery.*') ? 'active' : '' }}"
                @if (request()->routeIs('gallery.*'))
                aria-current="page"
                @endif
                >
                Galeri
            </a>


            <a
                href="{{ route('about') }}"
                class="{{ request()->routeIs('about') ? 'active' : '' }}"
                @if (request()->routeIs('about'))
                aria-current="page"
                @endif
                >
                Tentang Kami
            </a>

        </div>


        {{-- ACTIONS --}}
        <div class="navbar-actions">

            @guest

            {{-- CUSTOMER BELUM LOGIN --}}
            <a
                href="{{ route('login') }}"
                class="btn btn-ghost">
                <i class="bi bi-person"></i>

                Login
            </a>

            @else
            <span class="navbar-customer">
                <i class="bi bi-person-circle"></i>
                {{ auth()->user()->name }}
            </span>

            <a href="{{ route('customer.bookings.index') }}"
                class="btn btn-ghost">
                <i class="bi bi-clock-history"></i>
                Riwayat
            </a>

            <form action="{{ route('logout') }}"
                method="POST"
                class="navbar-logout">
                @csrf

                <button type="submit" class="btn btn-ghost">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
            @endguest


            {{-- BOOKING NANTI DISAMBUNG ABI --}}
            <a
                href="#"
                class="btn btn-primary">
                Booking

                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    </div>

</nav>