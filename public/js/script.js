/* =========================================================
   1. NAVBAR MOBILE TOGGLE
   ========================================================= */

const navbarToggle = document.getElementById('navbarToggle');
const navbarMenu = document.getElementById('navbarMenu');
const navbarActions = document.querySelector('.navbar-actions');

if (navbarToggle && navbarMenu && navbarActions) {
    navbarToggle.addEventListener('click', function () {

        // Tampilkan / sembunyikan menu navigasi
        navbarMenu.classList.toggle('active');

        // Tampilkan / sembunyikan tombol Login dan Booking
        navbarActions.classList.toggle('active');

        // Cek apakah menu sedang terbuka
        const isOpen = navbarMenu.classList.contains('active');

        // Ubah icon hamburger menjadi icon X ketika menu terbuka
        navbarToggle.innerHTML = isOpen
            ? '<i class="bi bi-x-lg"></i>'
            : '<i class="bi bi-list"></i>';

        // Membantu accessibility
        navbarToggle.setAttribute('aria-expanded', isOpen);
    });
}


/* =========================================================
   2. NAVBAR SCROLL EFFECT
   ========================================================= */

const navbar = document.getElementById('mainNavbar');

if (navbar) {
    window.addEventListener('scroll', function () {

        // Jika halaman discroll lebih dari 10px,
        // tambahkan class "scrolled"
        if (window.scrollY > 10) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

    });
}


/* =========================================================
   3. FUTURE GLOBAL INTERACTIONS
   ========================================================= */

/*
   Logic global lain nanti bisa ditaruh di sini.

   Contoh:
   - dropdown
   - modal
   - alert close
   - back to top
   - animation on scroll
*/