<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Aura Studio')
    </title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CSS DASAR ADMIN --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-branches.css') }}">

    {{-- CSS TAMBAHAN PER HALAMAN --}}
    @stack('styles')
</head>

<body>

    <div class="admin-wrapper">

        {{-- ============================================
         SIDEBAR
         ============================================ --}}
        <aside class="admin-sidebar">

            <div class="admin-brand">

                <strong>
                    Aura Studio
                </strong>

                <span>
                    Admin Panel
                </span>

            </div>


            <nav>

                {{-- KELOLA CABANG --}}
                <a
                    href="{{ route('admin.branches.index') }}"
                    class="{{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>

                    Kelola Cabang
                </a>


                {{-- KELOLA GALERI --}}
                <a
                    href="{{ route('admin.galleries.index') }}"
                    class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>

                    Kelola Galeri
                </a>


                {{-- KELOLA JADWAL --}}
                <a
                    href="{{ route('admin.schedules.index') }}"
                    class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>

                    Kelola Jadwal
                </a>


                {{-- DAFTAR BOOKING --}}
                <a
                    href="{{ route('admin.bookings.index') }}"
                    class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i>

                    Daftar Booking
                </a>


                {{-- VERIFIKASI PEMBAYARAN --}}
                <a
                    href="{{ route('admin.payments.index') }}"
                    class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>

                    Verifikasi Pembayaran
                </a>


                {{-- KELOLA CUSTOMER --}}
                <a
                    href="{{ route('admin.customers.index') }}"
                    class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>

                    Kelola Customer
                </a>

            </nav>


            <a
                href="{{ route('home') }}"
                class="admin-back-site">
                <i class="bi bi-arrow-left"></i>

                Kembali ke Website
            </a>

        </aside>



        {{-- ============================================
         MAIN CONTENT
         ============================================ --}}
        <main class="admin-main">

            <header class="admin-topbar">

                <div>
                    <strong>
                        Administration
                    </strong>
                </div>


                <div class="admin-user">

                    <i class="bi bi-person-circle"></i>

                    Admin

                </div>

            </header>


            <div class="admin-content">

                @yield('content')

            </div>

        </main>

    </div>

</body>

</html>