@extends('layouts.app')

@section('title', 'Daftar Paket - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/packages.css') }}">
@endpush

@section('content')

    @php
        $includesOf = function ($p) {
            $raw = (string) $p->package_includes;
            $list = collect(preg_split('/[\r\n;]+/', $raw))->map(fn($i) => trim($i, " \t-•"))->filter();
            if ($list->count() < 2)
                $list = collect(explode(',', $raw))->map(fn($i) => trim($i))->filter();
            return $list->values();
        };
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="pkg-hero has-ring">
        <div class="container">
            <nav class="svc-crumb is-center" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span><strong>Paket</strong>
            </nav>
            <span class="svc-pill">Paket Aura Studio</span>
            <h1>Temukan paket foto <em>yang tepat.</em></h1>
            <p class="svc-lead">Pilih paket berdasarkan jenis layanan, durasi, jumlah peserta, dan hasil foto yang kamu
                butuhkan.</p>
            <ul class="pkg-hero-tags">
                @foreach ($services as $service)
                    <li><i class="bi bi-dot"></i>{{ $service->name }}</li>
                @endforeach
            </ul>
        </div>
    </section>


    {{-- ============ DAFTAR ============ --}}
    <section class="svc-section" id="daftar-paket">
        <div class="container">

            <div class="pkg-toolbar">
                <div class="pkg-tabs" role="tablist" aria-label="Filter layanan">
                    <button type="button" class="is-active" data-filter="all">Semua Paket</button>
                    @foreach ($services as $service)
                        <button type="button" data-filter="{{ $service->id }}">{{ $service->name }}</button>
                    @endforeach
                </div>
                <p class="pkg-count">Menampilkan <strong id="pkgCount">{{ $packages->count() }}</strong> paket</p>
            </div>

            <div class="pkg-grid" id="pkgGrid">
                @forelse ($packages as $package)
                    @php $items = $includesOf($package); @endphp
                    <article class="pkg-card" data-service="{{ $package->service_id }}">
                        <span class="pkg-tag">{{ $package->service->name ?? 'Paket' }}</span>
                        <h2>{{ $package->name }}</h2>
                        <div class="pkg-price">Rp{{ number_format($package->price, 0, ',', '.') }}<small>/ sesi</small></div>

                        <dl class="pkg-stats">
                            <div>
                                <dt>Durasi</dt>
                                <dd>{{ $package->duration_minutes }} menit</dd>
                            </div>
                            @if ($package->max_people)
                                <div>
                                    <dt>Peserta</dt>
                                    <dd>Maks {{ $package->max_people }} org</dd>
                                </div>
                            @endif
                            @if ($package->photo_count)
                                <div>
                                    <dt>Hasil</dt>
                                    <dd>{{ $package->photo_count }} foto</dd>
                                </div>
                            @endif
                        </dl>

                        @if ($items->isNotEmpty())
                            <ul class="svc-checks">
                                @foreach ($items->take(5) as $item)
                                    <li><i class="bi bi-check-circle-fill"></i>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="pkg-desc">{{ \Illuminate\Support\Str::limit($package->description, 110) }}</p>
                        @endif

                        <div class="pkg-card-foot">
                            <a href="{{ route('packages.show', $package->id) }}" class="btn btn-primary">Lihat Detail</a>
                            <a href="{{ route('services.show', $package->service->slug) }}" class="svc-link">Tentang layanan <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </article>
                @empty
                    <p class="svc-empty">Belum ada paket tersedia.</p>
                @endforelse
            </div>

            <p class="svc-note is-center">
                <i class="bi bi-info-circle"></i>
                <span>
                    <strong>Paket dan jadwal dipilih terpisah.</strong>
                    Paket menentukan durasi dan isi sesi.
                    Detail lokasi, sumber daya, tanggal, dan jam
                    disesuaikan dengan layanan saat booking.
                </span>
            </p>

        </div>
    </section>


    {{-- ============ CTA ============ --}}
    <section class="svc-cta">
        <div class="container">
            <div class="svc-cta-box has-ring">
                <span class="section-eyebrow">Konsultasi gratis</span>
                <h2>Masih bingung memilih paket?</h2>
                <p>Pelajari perbedaan tiap layanan, lalu pilih paket yang paling pas untuk momenmu.</p>
                <div class="svc-actions is-center">
                    <a href="{{ route('services.index') }}" class="btn btn-primary">Lihat Layanan</a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        (function () {
            var tabs = document.querySelectorAll('.pkg-tabs button');
            var cards = document.querySelectorAll('.pkg-card');
            var count = document.getElementById('pkgCount');
            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var f = tab.dataset.filter, n = 0;
                    tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
                    cards.forEach(function (c) {
                        var show = f === 'all' || c.dataset.service === f;
                        c.hidden = !show;
                        if (show) { n++; c.classList.add('in'); }
                    });
                    if (count) count.textContent = n;
                });
            });
        })();
    </script>
@endpush