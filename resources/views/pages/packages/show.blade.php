@extends('layouts.app')

@section('title', $package->name . ' - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/packages.css') }}">
@endpush

@section('content')

@php
    $raw = (string) $package->package_includes;
    $items = collect(preg_split('/[\r\n;]+/', $raw))->map(fn ($i) => trim($i, " \t-•"))->filter();
    if ($items->count() < 2) $items = collect(explode(',', $raw))->map(fn ($i) => trim($i))->filter();
    $items = $items->values();

    $icon = $service->name === 'Self Photo Studio' ? 'bi-person-bounding-box' : ($service->is_on_location ? 'bi-geo-alt' : 'bi-camera');
    $photo = $service->image ?? null;
    $branches = $branches ?? collect();
    $galleries = $galleries ?? collect();
    $relatedPackages = $relatedPackages ?? collect();
    $bookUrl = \Illuminate\Support\Facades\Route::has('customer.bookings.create')
        ? route('customer.bookings.create', ['package' => $package->id])
        : (auth()->check() ? route('services.show', $service->slug) : route('login'));

    $steps = [['bi-geo-alt', 'Pilih cabang', 'Tentukan cabang yang paling nyaman untukmu.']];
    if ($service->requires_photographer) $steps[] = ['bi-person-video2', 'Pilih fotografer', 'Pilih fotografer yang cocok dengan gayamu.'];
    $steps[] = ['bi-calendar-event', 'Pilih tanggal & jam', 'Pilih slot yang cukup untuk ' . $package->duration_minutes . ' menit sesi.'];
    $steps[] = ['bi-credit-card', 'Bayar & konfirmasi', 'Selesaikan pembayaran untuk mengamankan jadwalmu.'];
@endphp

{{-- ============ HERO ============ --}}
<section class="pkg-dhero has-ring">
    <div class="container">

        <nav class="svc-crumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span>
            <a href="{{ route('packages.index') }}">Paket</a><span class="sep">/</span>
            <a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a><span class="sep">/</span>
            <strong>{{ $package->name }}</strong>
        </nav>

        <div class="pkg-dhero-grid">

            <div class="pkg-visual">
                <div class="pkg-visual-main">
                    @if ($photo)
                        <img src="{{ asset($photo) }}" alt="{{ $package->name }}">
                    @else
                        <div class="svc-ph">
                            <i class="bi {{ $icon }}"></i>
                            <span>{{ $package->name }}</span>
                        </div>
                    @endif
                </div>
                @if ($galleries->count() >= 2)
                    <div class="pkg-thumbs">
                        @foreach ($galleries->take(3) as $shot)
                            <img src="{{ asset($shot->image) }}" alt="{{ $shot->title }}" loading="lazy">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pkg-info">
                <div class="pkg-info-tags">
                    <span class="pkg-tag">{{ $service->name }}</span>
                </div>
                <h1>{{ $package->name }}</h1>
                <p>{{ $package->description }}</p>

                <div class="pkg-pricebox">
                    <span>Harga paket</span>
                    <strong>Rp{{ number_format($package->price, 0, ',', '.') }}</strong>
                    <small>Harga per sesi. Jadwal dipilih saat booking.</small>
                </div>

                <dl class="pkg-facts">
                    <div><i class="bi bi-clock"></i><dt>Durasi</dt><dd>{{ $package->duration_minutes }} menit</dd></div>
                    @if ($package->max_people)
                        <div><i class="bi bi-people"></i><dt>Peserta</dt><dd>Maks. {{ $package->max_people }} orang</dd></div>
                    @endif
                    @if ($package->photo_count)
                        <div><i class="bi bi-images"></i><dt>Hasil foto</dt><dd>{{ $package->photo_count }} foto</dd></div>
                    @endif
                    <div><i class="bi bi-building"></i><dt>Fasilitas</dt><dd>{{ $service->requires_room ? 'Ruang studio' : ($service->is_on_location ? 'Di lokasi' : 'Aura Studio') }}</dd></div>
                </dl>

                <div class="pkg-info-actions">
                    <a href="{{ $bookUrl }}" class="btn btn-primary">Booking {{ $package->name }} <i class="bi bi-arrow-right"></i></a>
                    <a href="{{ route('packages.index') }}" class="btn btn-outline">Kembali ke Semua Paket</a>
                    <small>Cabang, fotografer, tanggal, dan sesi waktu dipilih di alur booking.</small>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============ YANG DIDAPATKAN ============ --}}
<section class="svc-section">
    <div class="container">
        <div class="pkg-got">

            <div>
                <span class="section-eyebrow">Keuntungan sesi</span>
                <h2>Yang kamu dapatkan</h2>
                @if ($items->isNotEmpty())
                    <ul class="pkg-includes">
                        @foreach ($items as $item)
                            <li><i class="bi bi-check-circle-fill"></i>{{ $item }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="pkg-desc">{{ $package->description }}</p>
                @endif
            </div>

            <div>
                <span class="section-eyebrow">Layanan</span>
                <h2>Tentang {{ $service->name }}</h2>
                <ul class="svc-spec-list pkg-spec">
                    <li><span class="svc-spec-ico"><i class="bi bi-camera"></i></span>
                        <div><span>Fotografer</span><strong>{{ $service->requires_photographer ? 'Tersedia' : 'Tidak diperlukan' }}</strong></div></li>
                    <li><span class="svc-spec-ico"><i class="bi bi-building"></i></span>
                        <div><span>Studio</span><strong>{{ $service->requires_room ? 'Menggunakan studio' : 'Tidak menggunakan studio' }}</strong></div></li>
                    <li><span class="svc-spec-ico"><i class="bi bi-geo-alt"></i></span>
                        <div><span>Lokasi</span><strong>{{ $service->is_on_location ? 'Lokasi pilihan pelanggan' : 'Aura Studio' }}</strong></div></li>
                </ul>
                <a href="{{ route('services.show', $service->slug) }}" class="svc-link">Lihat layanan <i class="bi bi-arrow-right"></i></a>
            </div>

        </div>
    </div>
</section>


{{-- ============ LANGKAH ============ --}}
<section class="svc-section is-alt">
    <div class="container">
        <div class="svc-head">
            <span class="section-eyebrow">Alur reservasi</span>
            <h2>Langkah selanjutnya</h2>
            <p>Tahapan mudah untuk mengamankan sesi {{ $package->name }}.</p>
        </div>
        <ol class="svc-steps" style="--n: {{ count($steps) }}">
            @foreach ($steps as [$sIcon, $sTitle, $sText])
                <li class="svc-step">
                    <div class="svc-step-head"><i class="bi {{ $sIcon }}"></i></div>
                    <h3>{{ $sTitle }}</h3>
                    <p>{{ $sText }}</p>
                </li>
            @endforeach
        </ol>
        <p class="svc-note is-center">
            <i class="bi bi-info-circle"></i>
            <span><strong>{{ $package->name }}</strong> otomatis terpilih saat kamu menekan tombol booking dari halaman ini.</span>
        </p>
    </div>
</section>


{{-- ============ CABANG ============ --}}
@if ($branches->isNotEmpty())
<section class="svc-section">
    <div class="container">
        <div class="svc-head is-left">
            <span class="section-eyebrow">Lokasi</span>
            <h2>Pilih cabang saat booking</h2>
            <p>Paket ini tersedia di cabang berikut.</p>
        </div>
        <div class="svc-cards">
            @foreach ($branches as $branch)
                <article class="svc-branch">
                    <span class="svc-branch-ico"><i class="bi bi-geo-alt"></i></span>
                    <h3>{{ $branch->name }}</h3>
                    @if ($branch->address)<p>{{ $branch->address }}</p>@endif
                    <div class="svc-meta">
                        @if ($branch->opening_time)
                            <span><i class="bi bi-clock"></i>{{ substr($branch->opening_time, 0, 5) }} – {{ substr($branch->closing_time, 0, 5) }} WIB</span>
                        @endif
                    </div>
                    <div class="svc-branch-actions">
                        <a href="{{ route('branches.show', $branch->id) }}" class="svc-link">Lihat cabang <i class="bi bi-arrow-right"></i></a>
                    </div>
                </article>
            @endforeach
        </div>
        <p class="pkg-foot-note">Ketersediaan fotografer, tata letak studio, dan jadwal bisa berbeda di tiap cabang.</p>
    </div>
</section>
@endif


{{-- ============ GALERI ============ --}}
@if ($galleries->isNotEmpty())
<section class="svc-section svc-band">
    <div class="container">
        <div class="svc-head">
            <span class="section-eyebrow">Hasil foto</span>
            <h2>Inspirasi {{ $service->name }}</h2>
        </div>
        <div class="svc-gallery">
            @foreach ($galleries as $shot)
                <figure class="svc-shot">
                    <img src="{{ asset($shot->image) }}" alt="{{ $shot->title }}" loading="lazy">
                    @if ($shot->title)<figcaption>{{ $shot->title }}</figcaption>@endif
                </figure>
            @endforeach
        </div>
        <div class="svc-actions is-center" style="margin-top:36px">
            <a href="{{ route('gallery.index') }}" class="btn btn-outline-light">Lihat Galeri Lengkap</a>
        </div>
    </div>
</section>
@endif


{{-- ============ PAKET LAIN ============ --}}
@if ($relatedPackages->isNotEmpty())
<section class="svc-section">
    <div class="container">
        <div class="svc-head is-left">
            <span class="section-eyebrow">Pilihan lainnya</span>
            <h2>Paket {{ $service->name }} lainnya</h2>
        </div>
        <div class="pkg-grid is-related">
            @foreach ($relatedPackages as $other)
                <article class="pkg-card">
                    <span class="pkg-tag">{{ $service->name }}</span>
                    <h3>{{ $other->name }}</h3>
                    <div class="pkg-price">Rp{{ number_format($other->price, 0, ',', '.') }}<small>/ sesi</small></div>
                    <dl class="pkg-stats">
                        <div><dt>Durasi</dt><dd>{{ $other->duration_minutes }} menit</dd></div>
                        @if ($other->max_people)<div><dt>Peserta</dt><dd>Maks {{ $other->max_people }} org</dd></div>@endif
                        @if ($other->photo_count)<div><dt>Hasil</dt><dd>{{ $other->photo_count }} foto</dd></div>@endif
                    </dl>
                    <div class="pkg-card-foot">
                        <a href="{{ route('packages.show', $other->id) }}" class="btn btn-outline">Lihat Detail</a>
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
            <span class="section-eyebrow">Reservasi</span>
            <h2>Siap mengabadikan momenmu dengan {{ $package->name }}?</h2>
            <p>Lanjut ke booking untuk memilih cabang, jadwal, dan menyelesaikan pembayaran.</p>
            <div class="svc-actions is-center">
                <a href="{{ $bookUrl }}" class="btn btn-primary">Booking {{ $package->name }} <i class="bi bi-arrow-right"></i></a>
                <a href="{{ route('packages.index') }}" class="btn btn-outline-light">Lihat Paket Lain</a>
            </div>
        </div>
    </div>
</section>

@endsection
