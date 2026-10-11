<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#f4f1ec">

    <title>@yield('title', 'Reservasi — Aura Studio')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..700;1,9..144,300..700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">

    @stack('styles')
</head>

<body class="booking-body">

    <header class="booking-topbar">
        <div class="booking-topbar-inner">

            <a href="{{ route('home') }}" class="booking-brand">
                <span class="booking-brand-mark">A</span>

                <span>
                    <strong>Aura Studio</strong>
                    <small>Photography & Space</small>
                </span>
            </a>

            <div class="booking-topbar-title">
                Reservasi Aura Studio
            </div>

            <div class="booking-account">
                <div class="booking-account-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>

                <div>
                    <strong>{{ Auth::user()->name ?? 'Customer' }}</strong>
                    <small>Customer</small>
                </div>
            </div>

        </div>
    </header>

    @include('customer.bookings.partials.steps')

    <main class="booking-main">
        @yield('content')
    </main>

    @stack('scripts')

</body>

</html>