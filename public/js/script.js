/* =========================================================
   AURA STUDIO — GLOBAL SCRIPT
   1. Navbar mobile toggle
   2. Navbar scroll effect
   3. Reveal on scroll
   ========================================================= */

(function () {
    'use strict';

    var navbar = document.getElementById('mainNavbar');
    var toggle = document.getElementById('navbarToggle');
    var menu = document.getElementById('navbarMenu');
    var actions = document.querySelector('.navbar-actions');
    var MOBILE_BREAKPOINT = 1160; // harus sama dengan breakpoint navbar di style.css


    /* -----------------------------------------------------
       1. NAVBAR MOBILE TOGGLE
       ----------------------------------------------------- */

    function setMenu(open) {
        if (!toggle || !menu || !actions) return;

        menu.classList.toggle('active', open);
        actions.classList.toggle('active', open);

        toggle.innerHTML = open
            ? '<i class="bi bi-x-lg"></i>'
            : '<i class="bi bi-list"></i>';

        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    }

    if (toggle && menu && actions) {
        toggle.addEventListener('click', function () {
            setMenu(!menu.classList.contains('active'));
        });

        // Tutup menu setelah salah satu link dipilih
        menu.addEventListener('click', function (event) {
            if (event.target.closest('a')) setMenu(false);
        });

        // Tutup dengan tombol Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && menu.classList.contains('active')) {
                setMenu(false);
                toggle.focus();
            }
        });

        // Reset saat layar kembali lebar
        window.addEventListener('resize', function () {
            if (window.innerWidth > MOBILE_BREAKPOINT && menu.classList.contains('active')) {
                setMenu(false);
            }
        });
    }


    /* -----------------------------------------------------
       2. NAVBAR SCROLL EFFECT
       ----------------------------------------------------- */

    if (navbar) {
        var ticking = false;

        function updateNavbar() {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }, { passive: true });

        updateNavbar();
    }


    /* -----------------------------------------------------
       3. REVEAL ON SCROLL
       Elemen di bawah ini muncul bertahap saat masuk layar.
       Class "js" dipasang di <head>, jadi bila script ini gagal
       dimuat, semua konten tetap terlihat.
       ----------------------------------------------------- */

    var REVEAL_TARGETS = [
        '.home-section-heading',
        '.section-header-row',
        '.section-heading',
        '.services-grid > *',
        '.packages-grid > *',
        '.branches-grid > *',
        '.booking-step',
        '.gallery-item',
        '.gallery-empty',
        '.benefit-card',
        '.testimonial-card',
        '.final-cta-box',
        '.section-action',
        '.service-info-item',
        '.package-detail-price',
        '.package-detail-page .package-includes',
        '.svc-head',
        '.svc-row',
        '.svc-compare',
        '.svc-guide',
        '.svc-spec',
        '.svc-fac-copy',
        '.svc-plan',
        '.svc-step',
        '.svc-branch',
        '.svc-person',
        '.svc-shot',
        '.svc-faq details',
        '.svc-others',
        '.svc-cta-box',
        '.pkg-card',
        '.pkg-includes li',
        '.pkg-info',
        '.brn-room',
        '.brn-strip li',
        '.brn-contact',
        '.brn-map'
    ].join(',');

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var items = document.querySelectorAll(REVEAL_TARGETS);

    if (!items.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) return;

    // Beri jeda bertingkat (stagger) di antara saudara kandung
    var siblingCount = new Map();

    items.forEach(function (el) {
        var parent = el.parentElement;
        var index = siblingCount.get(parent) || 0;

        el.style.setProperty('--i', Math.min(index, 6));
        el.classList.add('reveal');

        siblingCount.set(parent, index + 1);
    });

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;

            entry.target.classList.add('in');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    items.forEach(function (el) {
        observer.observe(el);
    });
})();