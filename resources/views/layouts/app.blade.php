<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f4f1ec">
    <meta name="description" content="@yield('meta_description', 'Aura Studio — Photography & Space. Studio foto profesional, self photo, dan dokumentasi di lokasi pilihanmu.')">

    <title>@yield('title', 'Aura Studio')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Aktifkan gaya animasi hanya jika JavaScript berjalan --}}
    <script>document.documentElement.classList.add('js');</script>

    {{-- Google Fonts: Fraunces (judul) + Plus Jakarta Sans (isi) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght,SOFT@0,9..144,300..700,0..100;1,9..144,300..700,0..100&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- Global CSS (token, navbar, footer, komponen bersama) --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Page Specific CSS --}}
    @stack('styles')

</head>

<body id="top">

    <a href="#main" class="skip-link">Lewati ke konten utama</a>

    @include('partials.navbar')

    <main class="main-content" id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/script.js') }}" defer></script>

    @stack('scripts')

</body>

</html>