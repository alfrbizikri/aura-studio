@extends('layouts.app')

@section('title', 'Daftar Cabang - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/packages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/branches.css') }}">
@endpush

@section('content')

    @php
        // Kumpulan layanan unik dari semua cabang, dipakai untuk filter
        $allServices = $branches->flatMap->services->unique('id')->values();
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="brn-hero has-ring">
        <div class="container">
            <div class="brn-hero-grid">

                <div class="brn-hero-copy">
                    <nav class="svc-crumb" aria-label="Breadcrumb">
                        <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span><strong>Cabang</strong>
                    </nav>
                    <span class="svc-pill">Cabang Aura Studio</span>
                    <h1>Temukan studio <em>terdekat.</em></h1>
                    <p class="svc-lead">Pilih cabang Aura Studio, lalu lihat layanan, fasilitas, fotografer, dan jam
                        operasional di tiap lokasi.</p>
                </div>

                <ul class="brn-perks">
                    <li><i class="bi bi-geo-alt"></i>
                        <div><strong>{{ $branches->count() }} lokasi</strong><span>Siap dikunjungi</span></div>
                    </li>
                    <li><i class="bi bi-camera"></i>
                        <div><strong>Fasilitas pro</strong><span>Studio &amp; peralatan</span></div>
                    </li>
                    <li><i class="bi bi-calendar-check"></i>
                        <div><strong>Jadwal per cabang</strong><span>Dipilih saat booking</span></div>
                    </li>
                </ul>

            </div>
        </div>
    </section>


    {{-- ============ DAFTAR ============ --}}
    <section class="svc-section" id="daftar-cabang">
        <div class="container">

            <div class="brn-filter">
                <label class="brn-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="brnSearch" placeholder="Cari nama cabang atau kota…" autocomplete="off">
                </label>
                <div class="pkg-tabs" id="brnTabs">
                    <button type="button" class="is-active" data-filter="all">Semua Layanan</button>
                    @foreach ($allServices as $service)
                        <button type="button" data-filter="{{ $service->id }}">{{ $service->name }}</button>
                    @endforeach
                </div>
            </div>
            <p class="pkg-count brn-count">Menampilkan <strong id="brnCount">{{ $branches->count() }}</strong> cabang</p>

            <div class="svc-rows" id="brnList">
                @forelse ($branches as $branch)
                    @php
                        // GAMBAR CABANG: isi kolom `image` di tabel branches (contoh: images/branches/jakarta.jpg)
                        $branchImage = $branch->image ?? null;
                    @endphp
                    <article class="svc-row brn-item" data-name="{{ strtolower($branch->name . ' ' . $branch->address) }}"
                        data-services="{{ $branch->services->pluck('id')->implode(',') }}">

                        <div class="svc-row-media has-ring">
                            {{-- 🖼 GAMBAR: foto gedung/studio cabang → ganti isi asset() dengan path fotomu --}}
                            @if ($branchImage)
                                <img src="{{ asset($branchImage) }}" alt="{{ $branch->name }}" loading="lazy">
                            @else
                                <div class="svc-ph {{ ['', 'tone-2', 'tone-3'][$loop->index % 3] }}">
                                    <i class="bi bi-building"></i>
                                    <span>{{ $branch->name }}</span>
                                </div>
                            @endif
                            <span class="svc-row-badge"><i class="bi bi-geo-alt"></i>Cabang aktif</span>
                        </div>

                        <div class="svc-row-body">
                            <span class="svc-pill">Aura Studio</span>
                            <h2>{{ $branch->name }}</h2>
                            <p class="brn-address"><i class="bi bi-geo-alt"></i>{{ $branch->address }}</p>

                            <div class="brn-meta">
                                @if ($branch->opening_time)
                                    <span><i class="bi bi-clock"></i>{{ substr($branch->opening_time, 0, 5) }} –
                                        {{ substr($branch->closing_time, 0, 5) }} WIB</span>
                                @endif
                                @if ($branch->phone)
                                    <span><i class="bi bi-telephone"></i>{{ $branch->phone }}</span>
                                @endif
                            </div>

                            @if ($branch->services->isNotEmpty())
                                <div>
                                    <span class="brn-label">Layanan tersedia</span>
                                    <div class="brn-tags">
                                        @foreach ($branch->services as $service)
                                            <span>{{ $service->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="svc-actions">
                                <a href="{{ route('branches.show', $branch->id) }}" class="btn btn-primary">Lihat Detail Cabang
                                    <i class="bi bi-arrow-right"></i></a>
                                @if ($branch->maps_url)
                                    <a href="{{ $branch->maps_url }}" target="_blank" rel="noopener" class="btn btn-outline"><i
                                            class="bi bi-map"></i> Lihat di Peta</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="svc-empty">Belum ada cabang tersedia.</p>
                @endforelse
            </div>

            <p class="svc-empty" id="brnEmpty" hidden>Tidak ada cabang yang cocok dengan pencarianmu.</p>

            <p class="svc-note is-center">
                <i class="bi bi-info-circle"></i>
                <span>
                    <strong>Layanan berbeda di setiap cabang.</strong>
                    Pilihan paket, sumber daya, lokasi, dan jadwal
                    menyesuaikan layanan serta cabang yang dipilih saat booking.
                </span>
            </p>

        </div>
    </section>


    {{-- ============ ALUR ============ --}}
    <section class="svc-section is-alt">
        <div class="container">
            <div class="svc-head">
                <span class="section-eyebrow">Alur reservasi</span>
                <h2>Memilih cabang saat booking</h2>
                <p>Sistem hanya menampilkan cabang yang menyediakan layanan pilihanmu.</p>
            </div>
            <ol class="svc-steps" style="--n: 3">
                <li class="svc-step">
                    <div class="svc-step-head"><i class="bi bi-sliders"></i></div>
                    <h3>Pilih layanan</h3>
                    <p>Tentukan format sesi: studio terarah, foto mandiri, atau di lokasi.</p>
                </li>
                <li class="svc-step">
                    <div class="svc-step-head"><i class="bi bi-buildings"></i></div>
                    <h3>Pilih cabang</h3>
                    <p>
                        Pilih cabang yang menyediakan layanan tersebut.
                        Untuk layanan On Location, cabang berperan sebagai
                        cabang pendukung layanan.
                    </p>
                </li>
                <li class="svc-step">
                    <div class="svc-step-head"><i class="bi bi-calendar-check"></i></div>
                    <h3>Pilih paket &amp; jadwal</h3>
                    <p>Pilih paket, lalu tanggal dan sesi waktu yang masih tersedia.</p>
                </li>
            </ol>
        </div>
    </section>


    {{-- ============ CTA ============ --}}
    <section class="svc-cta">
        <div class="container">
            <div class="svc-cta-box has-ring">
                <span class="section-eyebrow">Mulai pengalamanmu</span>
                <h2>Sudah menemukan cabang yang tepat?</h2>
                <p>Lanjut pilih layanan dan paket, lalu amankan jadwalmu.</p>
                <div class="svc-actions is-center">
                    <a href="{{ route('packages.index') }}" class="btn btn-primary">Lihat Paket</a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-light">Lihat Layanan</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        (function () {
            var items = document.querySelectorAll('.brn-item');
            var tabs = document.querySelectorAll('#brnTabs button');
            var search = document.getElementById('brnSearch');
            var count = document.getElementById('brnCount');
            var empty = document.getElementById('brnEmpty');
            var filter = 'all';

            function apply() {
                var q = (search.value || '').trim().toLowerCase(), n = 0;
                items.forEach(function (el) {
                    var okName = !q || el.dataset.name.indexOf(q) !== -1;
                    var okSvc = filter === 'all' || el.dataset.services.split(',').indexOf(filter) !== -1;
                    el.hidden = !(okName && okSvc);
                    if (!el.hidden) { n++; el.classList.add('in'); }
                });
                count.textContent = n;
                empty.hidden = n !== 0 || !items.length;
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    filter = tab.dataset.filter;
                    tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
                    apply();
                });
            });
            search.addEventListener('input', apply);
        })();
    </script>
@endpush