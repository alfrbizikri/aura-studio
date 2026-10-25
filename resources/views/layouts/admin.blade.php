<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Aura Studio')
    </title>

    {{-- GOOGLE FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">

    {{-- BOOTSTRAP ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CSS DASAR ADMIN --}}
    <link rel="stylesheet" href="{{ asset('css/admin-branches.css') }}">

    {{-- CSS TAMBAHAN PER HALAMAN --}}
    @stack('styles')
</head>

<body>

    <div class="admin-wrapper" id="adminWrapper">

        {{-- =========================================================
        SIDEBAR
        ========================================================== --}}
        <aside class="admin-sidebar" id="adminSidebar">

            {{-- BRAND --}}
            <div class="admin-brand">

                <span class="admin-brand-mark">
                    <i class="bi bi-camera"></i>
                </span>

                <div>
                    <strong>
                        Aura Studio
                    </strong>

                    <span>
                        Admin Panel
                    </span>
                </div>

            </div>


            {{-- NAVIGATION --}}
            <nav aria-label="Menu admin">

                {{-- =================================================
                MASTER DATA
                ================================================== --}}
                <small class="admin-nav-label">
                    Master Data
                </small>

                {{-- CABANG --}}
                <a href="{{ route('admin.branches.index') }}"
                    class="{{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>

                    Kelola Cabang
                </a>


                {{-- LAYANAN --}}
                <a href="{{ route('admin.services.index') }}"
                    class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="bi bi-camera"></i>

                    Kelola Layanan
                </a>


                {{-- PAKET --}}
                <a href="{{ route('admin.packages.index') }}"
                    class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <i class="bi bi-box"></i>

                    Kelola Paket
                </a>


                {{-- STUDIO ROOM --}}
                <a href="{{ route('admin.studio-rooms.index') }}"
                    class="{{ request()->routeIs('admin.studio-rooms.*') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i>

                    Kelola Studio Room
                </a>


                {{-- FOTOGRAFER --}}
                <a href="{{ route('admin.photographers.index') }}"
                    class="{{ request()->routeIs('admin.photographers.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>

                    Kelola Fotografer
                </a>


                {{-- GALERI --}}
                <a href="{{ route('admin.galleries.index') }}"
                    class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>

                    Kelola Galeri
                </a>


                {{-- JADWAL --}}
                <a href="{{ route('admin.schedules.index') }}"
                    class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>

                    Kelola Jadwal
                </a>


                {{-- =================================================
                TRANSAKSI
                ================================================== --}}
                <small class="admin-nav-label">
                    Transaksi
                </small>

                {{-- BOOKING --}}
                <a href="{{ route('admin.bookings.index') }}"
                    class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i>

                    Daftar Booking
                </a>


                {{-- PEMBAYARAN --}}
                <a href="{{ route('admin.payments.index') }}"
                    class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>

                    Verifikasi Pembayaran
                </a>


                {{-- =================================================
                PENGGUNA
                ================================================== --}}
                <small class="admin-nav-label">
                    Pengguna
                </small>

                {{-- CUSTOMER --}}
                <a href="{{ route('admin.customers.index') }}"
                    class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>

                    Kelola Customer
                </a>

                {{-- MANAJEMEN ULASAN --}}
                <a href="{{ route('admin.testimonials.index') }}"
                    class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <i class="bi bi-star-half"></i>
                    Manajemen Ulasan
                </a>


                {{-- =================================================
                ANALITIK
                ================================================== --}}
                <small class="admin-nav-label">
                    Analitik
                </small>

                {{-- LAPORAN --}}
                <a href="{{ route('admin.reports.index') }}"
                    class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>

                    Laporan
                </a>

            </nav>


            {{-- KEMBALI KE WEBSITE --}}
            <a href="{{ route('home') }}" class="admin-back-site">
                <i class="bi bi-arrow-left"></i>

                Kembali ke Website
            </a>


            {{-- LOGOUT --}}
            <form action="{{ route('admin.logout') }}" method="POST" class="admin-logout-form">
                @csrf

                <button type="submit" class="admin-logout-btn">
                    <i class="bi bi-box-arrow-right"></i>

                    Logout Admin
                </button>
            </form>

        </aside>


        {{-- OVERLAY MOBILE --}}
        <div class="admin-overlay" id="adminOverlay"></div>


        {{-- =========================================================
        MAIN CONTENT
        ========================================================== --}}
        <main class="admin-main">

            {{-- TOPBAR --}}
            <header class="admin-topbar">

                <div class="admin-topbar-left">

                    <button type="button" class="admin-menu-toggle" id="adminMenuToggle" aria-label="Buka menu"
                        aria-controls="adminSidebar" aria-expanded="false">
                        <i class="bi bi-list"></i>
                    </button>

                    <strong>
                        Administration
                    </strong>

                </div>


                {{-- ADMIN USER --}}
                <div class="admin-user">

                    <span class="admin-avatar">
                        {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                    </span>

                    <span>
                        {{ Auth::user()->name }}
                    </span>

                </div>

            </header>


            {{-- PAGE CONTENT --}}
            <div class="admin-content">
                @yield('content')
            </div>

        </main>

    </div>


    {{-- =============================================================
    SIDEBAR SCRIPT
    ============================================================== --}}
    <script>
        (function () {
            const wrapper = document.getElementById('adminWrapper');
            const toggle = document.getElementById('adminMenuToggle');
            const overlay = document.getElementById('adminOverlay');

            if (!wrapper || !toggle) {
                return;
            }

            function setOpen(open) {
                wrapper.classList.toggle(
                    'menu-open',
                    open
                );

                toggle.setAttribute(
                    'aria-expanded',
                    open ? 'true' : 'false'
                );

                toggle.setAttribute(
                    'aria-label',
                    open
                        ? 'Tutup menu'
                        : 'Buka menu'
                );
            }

            toggle.addEventListener(
                'click',
                function () {
                    setOpen(
                        !wrapper.classList.contains('menu-open')
                    );
                }
            );

            if (overlay) {
                overlay.addEventListener(
                    'click',
                    function () {
                        setOpen(false);
                    }
                );
            }

            document.addEventListener(
                'keydown',
                function (event) {
                    if (event.key === 'Escape') {
                        setOpen(false);
                    }
                }
            );
        })();
    </script>

    {{-- SCRIPT TAMBAHAN PER HALAMAN --}}
    @stack('scripts')

</body>

</html>