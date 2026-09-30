<nav class="navbar">
    <div class="container navbar-wrapper">

        <a href="{{ route('home') }}" class="navbar-brand">
            Aura Studio
        </a>

        <button class="navbar-toggle" id="navbarToggle">
            ☰
        </button>

        <div class="navbar-menu" id="navbarMenu">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="#">Layanan</a>
            <a href="#">Paket</a>
            <a href="#">Cabang</a>
            <a href="#">Fotografer</a>
            <a href="#">Galeri</a>
            <a href="#">Tentang Kami</a>
        </div>

        <div class="navbar-actions">
            <a href="#" class="btn btn-outline">Login</a>
            <a href="#" class="btn btn-primary">Booking</a>
        </div>

    </div>
</nav>