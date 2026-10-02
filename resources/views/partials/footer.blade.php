<footer class="footer">

    <div class="container footer-grid">

        {{-- BRAND --}}
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="footer-logo-link" aria-label="Aura Studio — Beranda">
                <img
                    src="{{ asset('images/aura-studio-logo.png') }}"
                    alt="Aura Studio"
                    class="footer-logo"
                >
            </a>

            <p class="footer-description">
                Aura Studio menghadirkan pengalaman fotografi
                yang hangat, nyaman, dan profesional untuk setiap momen.
            </p>
        </div>


        {{-- NAVIGASI --}}
        <nav class="footer-links" aria-label="Navigasi footer">
            <h4>Navigasi</h4>

            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('services.index') }}">Layanan</a>
            <a href="{{ route('packages.index') }}">Paket</a>
            <a href="#">Cabang</a>
            <a href="#">Fotografer</a>
        </nav>


        {{-- INFORMASI --}}
        <nav class="footer-links" aria-label="Informasi">
            <h4>Informasi</h4>

            <a href="#">Galeri</a>
            <a href="#">Tentang Kami</a>
            <a href="#">Kontak</a>
        </nav>


        {{-- KONTAK --}}
        <div class="footer-contact">
            <h4>Hubungi Kami</h4>

            <div class="footer-contact-item">
                <i class="bi bi-telephone"></i>
                <span>0812-3456-7890</span>
            </div>

            <div class="footer-contact-item">
                <i class="bi bi-envelope"></i>
                <span>hello@aurastudio.com</span>
            </div>

            <div class="footer-contact-item">
                <i class="bi bi-geo-alt"></i>
                <span>Aceh, Indonesia</span>
            </div>

            <div class="footer-social">
                <a href="#" aria-label="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>

                <a href="#" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>

                <a href="#" aria-label="TikTok">
                    <i class="bi bi-tiktok"></i>
                </a>
            </div>
        </div>

    </div>


    {{-- BOTTOM --}}
    <div class="footer-bottom">
        <div class="container footer-bottom-wrapper">

            <p>
                &copy; {{ date('Y') }} Aura Studio.
                All rights reserved.
            </p>

            <a href="#top" class="footer-back-top">
                Kembali ke atas
                <i class="bi bi-arrow-up"></i>
            </a>

        </div>
    </div>

</footer>