@extends('layouts.app')

@section('title', 'Galeri Foto - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
@endpush

@section('content')

    @php
        $selectedService = request('service');
        $heroShots = $galleries->take(3)->values();
        $perPage = 9;
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="glr-hero has-ring">
        <div class="container">
            <div class="glr-hero-grid">

                <div class="glr-hero-copy">
                    <nav class="svc-crumb" aria-label="Breadcrumb">
                        <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span><strong>Galeri</strong>
                    </nav>
                    <span class="section-eyebrow">Kurasi karya Aura Studio</span>
                    <h1>Cerita yang diabadikan <em>dalam setiap bingkai.</em></h1>
                    <p class="svc-lead">Jelajahi hasil fotografi Aura Studio dari berbagai layanan dan temukan gaya visual
                        yang sesuai dengan momenmu.</p>
                    <div class="svc-actions">
                        <a href="{{ route('packages.index') }}" class="btn btn-primary">Mulai Booking</a>
                        <a href="{{ route('packages.index') }}" class="btn btn-outline">Lihat Paket</a>
                    </div>
                </div>

                <div class="glr-collage">
                    @foreach (range(0, 2) as $i)
                        @php $shot = $heroShots->get($i); @endphp
                        <div class="glr-collage-item">
                            {{-- 🖼 GAMBAR: 3 foto pertama galeri dipakai otomatis untuk kolase hero --}}
                            @if ($shot && $shot->image)
                                <img src="{{ asset('storage/' . $shot->image) }}" alt="{{ $shot->title }}">
                            @else
                                <div class="svc-ph tone-{{ $i + 1 }}"><i class="bi bi-images"></i></div>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>


    {{-- ============ GALERI ============ --}}
    <section class="svc-section">
        <div class="container">

            <div class="glr-head">
                <div>
                    <span class="section-eyebrow">Temukan inspirasi</span>
                    <h2>Galeri foto</h2>
                    <p>Pilih kategori layanan untuk melihat hasil fotografi yang paling sesuai dengan kebutuhanmu.</p>
                </div>
                <p class="glr-count">Menampilkan <strong id="glrCount">{{ $galleries->count() }}</strong> karya</p>
            </div>

            <div class="glr-tabs" id="glrTabs" role="tablist" aria-label="Filter layanan">
                <button type="button" data-filter="all" class="{{ $selectedService ? '' : 'is-active' }}">Semua
                    Karya</button>
                @foreach ($services as $service)
                    <button type="button" data-filter="{{ $service->id }}"
                        class="{{ (string) $selectedService === (string) $service->id ? 'is-active' : '' }}">{{ $service->name }}</button>
                @endforeach
            </div>

            <div class="glr-grid" id="glrGrid">
                @forelse ($galleries as $gallery)
                    <article class="glr-card" data-service="{{ $gallery->service_id }}" data-title="{{ $gallery->title }}"
                        data-desc="{{ $gallery->description }}" data-tag="{{ $gallery->service->name ?? '' }}">

                        <a href="{{ $gallery->image ? asset('storage/' . $gallery->image) : '#' }}" class="glr-photo"
                            data-lightbox>
                            {{-- 🖼 GAMBAR: foto galeri → kolom `image` di tabel galleries --}}
                            @if ($gallery->image)
                                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy">
                            @else
                                <div class="svc-ph tone-{{ ($loop->index % 3) + 1 }}"><i class="bi bi-image"></i></div>
                            @endif
                            @if ($gallery->service)
                                <span class="glr-tag">{{ $gallery->service->name }}</span>
                            @endif
                        </a>

                        <div class="glr-body">
                            <h3>{{ $gallery->title }}</h3>
                            @if ($gallery->description)
                                <p>{{ \Illuminate\Support\Str::limit($gallery->description, 90) }}</p>
                            @endif
                            <a href="{{ $gallery->image ? asset('storage/' . $gallery->image) : '#' }}" class="svc-link"
                                data-lightbox>Lihat
                                foto <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </article>
                @empty
                    <p class="svc-empty">Belum ada foto di galeri.</p>
                @endforelse
            </div>

            <p class="svc-empty" id="glrEmpty" hidden>Belum ada karya untuk kategori ini.</p>

            <div class="glr-more" id="glrMore" hidden>
                <button type="button" class="btn btn-outline" id="glrMoreBtn">Muat Lebih Banyak</button>
                <small>Tampilkan lebih banyak hasil fotografi Aura Studio.</small>
            </div>

        </div>
    </section>


    {{-- ============ LAYANAN ============ --}}
    @if ($services->isNotEmpty())
        <section class="svc-section is-alt">
            <div class="container">
                <div class="svc-head">
                    <span class="section-eyebrow">Temukan layanan</span>
                    <h2>Wujudkan inspirasi menjadi sesi anda</h2>
                    <p>Setiap karya berasal dari salah satu layanan Aura Studio. Pilih layanan yang paling sesuai dengan
                        kebutuhanmu.</p>
                </div>

                <div class="svc-cards">
                    @foreach ($services as $service)
                        <article class="glr-service">
                            <div class="glr-service-img">
                                {{-- 🖼 GAMBAR: foto layanan → kolom `image` di tabel services --}}
                                @if ($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" loading="lazy">
                                @else
                                    <div class="svc-ph tone-{{ ($loop->index % 3) + 1 }}"><i class="bi bi-camera"></i></div>
                                @endif
                            </div>
                            <div class="glr-service-body">
                                <h3>{{ $service->name }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($service->description, 90) }}</p>
                                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline">Lihat
                                    {{ $service->name }}</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ============ CTA ============ --}}
    <section class="svc-cta">
        <div class="container">
            <div class="svc-cta-box has-ring">
                <span class="section-eyebrow">Ciptakan cerita anda</span>
                <h2>Siap menjadi bagian dari galeri berikutnya?</h2>
                <p>Pilih layanan, paket, cabang, dan jadwal untuk memulai pengalaman fotografi bersama Aura Studio.</p>
                <div class="svc-actions is-center">
                    <a href="{{ route('packages.index') }}" class="btn btn-primary">Mulai Booking</a>
                    <a href="{{ route('packages.index') }}" class="btn btn-outline-light">Lihat Paket</a>
                </div>
            </div>
        </div>
    </section>


    {{-- Lightbox --}}
    <dialog class="glr-lightbox" id="glrBox" aria-label="Pratinjau foto">
        <button type="button" class="glr-lb-close" data-close aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        <button type="button" class="glr-lb-nav is-prev" data-prev aria-label="Foto sebelumnya"><i
                class="bi bi-chevron-left"></i></button>
        <figure>
            <img src="" alt="" id="glrBoxImg">
            <figcaption>
                <span id="glrBoxTag"></span>
                <strong id="glrBoxTitle"></strong>
                <p id="glrBoxDesc"></p>
            </figcaption>
        </figure>
        <button type="button" class="glr-lb-nav is-next" data-next aria-label="Foto berikutnya"><i
                class="bi bi-chevron-right"></i></button>
    </dialog>

@endsection

@push('scripts')
    <script>
        (function () {
            var PER_PAGE = {{ $perPage }};
            var cards = Array.prototype.slice.call(document.querySelectorAll('.glr-card'));
            var tabs = document.querySelectorAll('#glrTabs button');
            var count = document.getElementById('glrCount');
            var empty = document.getElementById('glrEmpty');
            var more = document.getElementById('glrMore');
            var moreBtn = document.getElementById('glrMoreBtn');
            var filter = (document.querySelector('#glrTabs .is-active') || {}).dataset.filter || 'all';
            var limit = PER_PAGE;

            function matches() {
                return cards.filter(function (c) { return filter === 'all' || c.dataset.service === filter; });
            }

            function render() {
                var list = matches();
                cards.forEach(function (c) { c.hidden = true; });
                list.forEach(function (c, i) {
                    c.hidden = i >= limit;
                    if (!c.hidden) c.classList.add('in');
                });
                count.textContent = list.length;
                empty.hidden = list.length !== 0 || !cards.length;
                more.hidden = list.length <= limit;
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    filter = tab.dataset.filter;
                    limit = PER_PAGE;
                    tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
                    render();
                });
            });

            if (moreBtn) moreBtn.addEventListener('click', function () { limit += PER_PAGE; render(); });
            render();

            /* ---------- Lightbox ---------- */
            var box = document.getElementById('glrBox');
            if (!box || typeof box.showModal !== 'function') return;
            var img = document.getElementById('glrBoxImg');
            var current = -1;

            function visible() { return matches().filter(function (c) { return !c.hidden; }); }

            function show(card) {
                var list = visible();
                current = list.indexOf(card);
                var src = card.querySelector('.glr-photo img');
                if (!src) return;
                img.src = src.currentSrc || src.src;
                img.alt = card.dataset.title;
                document.getElementById('glrBoxTag').textContent = card.dataset.tag;
                document.getElementById('glrBoxTitle').textContent = card.dataset.title;
                document.getElementById('glrBoxDesc').textContent = card.dataset.desc;
                if (!box.open) box.showModal();
            }

            function step(d) {
                var list = visible();
                if (!list.length) return;
                show(list[(current + d + list.length) % list.length]);
            }

            document.getElementById('glrGrid').addEventListener('click', function (e) {
                var link = e.target.closest('[data-lightbox]');
                if (!link) return;
                var card = link.closest('.glr-card');
                if (!card.querySelector('.glr-photo img')) { e.preventDefault(); return; }
                e.preventDefault();
                show(card);
            });

            box.addEventListener('click', function (e) {
                if (e.target === box || e.target.closest('[data-close]')) box.close();
                else if (e.target.closest('[data-prev]')) step(-1);
                else if (e.target.closest('[data-next]')) step(1);
            });

            box.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowLeft') step(-1);
                if (e.key === 'ArrowRight') step(1);
            });
        })();
    </script>
@endpush