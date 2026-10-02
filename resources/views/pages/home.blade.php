@extends('layouts.app')

@section('title', 'Aura Studio - Beranda')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')


{{-- =====================================================
     HERO
     ===================================================== --}}
<section class="home-hero">

    <div class="container">

        <div class="home-hero-grid">

            {{-- LEFT --}}
            <div class="home-hero-copy">

                <span class="hero-badge">
                    <i class="bi bi-stars"></i>
                    Abadikan Momen Terbaik Anda
                </span>

                <h1>
                    Setiap Momen Layak
                    <span>
                        Diabadikan dengan Sempurna
                    </span>
                </h1>

                <p>
                    Aura Studio menghadirkan pengalaman fotografi
                    berkualitas mulai dari sesi studio profesional,
                    self photo, hingga dokumentasi di lokasi pilihanmu.
                </p>

                <div class="hero-actions">

                    {{-- Booking dikerjakan Abi --}}
                    <a href="#" class="btn btn-primary">
                        Booking Sekarang
                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <a href="{{ route('services.index') }}" class="btn btn-outline">
                        Lihat Layanan
                    </a>

                </div>

                <div class="hero-note">

                    <i class="bi bi-check-circle-fill"></i>

                    <span>
                        Pilih cabang, paket, fotografer,
                        dan jadwal sesuai kebutuhanmu.
                    </span>

                </div>

            </div>


            {{-- RIGHT --}}
            <div class="home-hero-media">

                {{-- Motif diafragma kamera (dekoratif) --}}
                <div class="hero-aperture" aria-hidden="true">
                    <svg viewBox="0 0 100 100" focusable="false">
                        <circle cx="50" cy="50" r="49" pathLength="1" />
                        <circle class="ring-soft" cx="50" cy="50" r="38" pathLength="1" />
                        <circle class="ring-soft" cx="50" cy="50" r="14" pathLength="1" />

                        <line class="ring-soft" x1="62.93" y1="55.36" x2="95.27" y2="68.75" pathLength="1" />
                        <line class="ring-soft" x1="55.36" y1="62.93" x2="68.75" y2="95.27" pathLength="1" />
                        <line class="ring-soft" x1="44.64" y1="62.93" x2="31.25" y2="95.27" pathLength="1" />
                        <line class="ring-soft" x1="37.07" y1="55.36" x2="4.73" y2="68.75" pathLength="1" />
                        <line class="ring-soft" x1="37.07" y1="44.64" x2="4.73" y2="31.25" pathLength="1" />
                        <line class="ring-soft" x1="44.64" y1="37.07" x2="31.25" y2="4.73" pathLength="1" />
                        <line class="ring-soft" x1="55.36" y1="37.07" x2="68.75" y2="4.73" pathLength="1" />
                        <line class="ring-soft" x1="62.93" y1="44.64" x2="95.27" y2="31.25" pathLength="1" />
                    </svg>
                </div>

                <div class="hero-photo">

                    @if ($galleries->isNotEmpty())

                    <img
                        src="{{ asset('storage/' . $galleries->first()->image) }}"
                        alt="{{ $galleries->first()->title }}">

                    @else

                    <div class="hero-photo-placeholder">

                        <i class="bi bi-camera"></i>

                        <span>
                            Aura Studio
                        </span>

                    </div>

                    @endif


                    <div class="hero-rating">

                        <i class="bi bi-star-fill"></i>

                        <strong>
                            4.9/5
                        </strong>

                        <span>
                            Pengalaman fotografi
                        </span>

                    </div>


                    <div class="hero-booking-card">

                        <div class="hero-booking-icon">
                            <i class="bi bi-calendar2-check"></i>
                        </div>

                        <div>
                            <strong>
                                Booking Mudah
                            </strong>

                            <span>
                                Pilih jadwal sesuai waktu Anda
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     SERVICES
     ===================================================== --}}
<section class="home-services">

    <div class="container">

        <div class="home-section-heading">

            <span class="section-eyebrow">
                Layanan Kami
            </span>

            <h2>
                Pilih Pengalaman Foto
                yang Anda Inginkan
            </h2>

            <p>
                Temukan format fotografi yang paling tepat
                untuk kebutuhan dan momen spesial Anda.
            </p>

        </div>


        <div class="services-grid">

            @foreach ($services as $service)

            <article class="service-card">

                <div class="service-card-top">

                    <span class="service-tag">
                        {{ $service->is_on_location ? 'Di lokasi pilihanmu' : ($service->requires_photographer ? 'Dengan fotografer' : 'Foto mandiri') }}
                    </span>

                    <span class="service-card-icon">

                        @if ($service->name === 'Self Photo Studio')
                        <i class="bi bi-person-bounding-box"></i>

                        @elseif ($service->is_on_location)
                        <i class="bi bi-geo-alt"></i>

                        @else
                        <i class="bi bi-camera"></i>

                        @endif

                    </span>

                </div>


                <h3>
                    {{ $service->name }}
                </h3>


                <p>
                    {{ $service->description }}
                </p>


                <ul class="service-features">

                    @if ($service->requires_photographer)

                    <li>
                        <i class="bi bi-check-circle"></i>
                        Fotografer profesional
                    </li>

                    @else

                    <li>
                        <i class="bi bi-check-circle"></i>
                        Foto mandiri dan fleksibel
                    </li>

                    @endif


                    @if ($service->requires_room)

                    <li>
                        <i class="bi bi-check-circle"></i>
                        Studio yang nyaman
                    </li>

                    @endif


                    @if ($service->is_on_location)

                    <li>
                        <i class="bi bi-check-circle"></i>
                        Lokasi sesuai pilihan Anda
                    </li>

                    @endif

                </ul>


                <a href="{{ route('services.show', $service->slug) }}"
                    class="service-card-button">

                    Lihat Detail

                    <i class="bi bi-arrow-right"></i>

                </a>

            </article>

            @endforeach

        </div>

    </div>

</section>



{{-- =====================================================
     BOOKING STEPS
     ===================================================== --}}
@php
$bookingSteps = [
['icon' => 'bi-camera', 'title' => 'Pilih Layanan', 'text' => 'Studio, Self Photo, atau On Location.'],
['icon' => 'bi-geo-alt', 'title' => 'Pilih Cabang', 'text' => 'Tentukan studio yang paling sesuai.'],
['icon' => 'bi-box', 'title' => 'Pilih Paket', 'text' => 'Sesuaikan paket dengan kebutuhan.'],
['icon' => 'bi-person', 'title' => 'Pilih Fotografer', 'text' => 'Tentukan fotografer untuk sesi Anda.'],
['icon' => 'bi-calendar3', 'title' => 'Pilih Jadwal', 'text' => 'Tentukan tanggal dan jam tersedia.'],
['icon' => 'bi-credit-card', 'title' => 'Pembayaran', 'text' => 'Selesaikan pembayaran untuk booking.'],
];
@endphp

<section class="home-booking-flow">

    <div class="container">

        <div class="booking-flow-panel">

            <div class="home-section-heading">

                <span class="section-eyebrow">
                    Alur Pemesanan
                </span>

                <h2>
                    Booking Foto Lebih Mudah
                </h2>

                <p>
                    Selesaikan pemesanan hanya dalam
                    beberapa langkah sederhana.
                </p>

            </div>


            <div class="booking-steps-grid">

                @foreach ($bookingSteps as $step)

                <article class="booking-step">

                    <span class="step-number">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>

                    <i class="bi {{ $step['icon'] }}"></i>

                    <strong>
                        {{ $step['title'] }}
                    </strong>

                    <p>
                        {{ $step['text'] }}
                    </p>

                </article>

                @endforeach

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     PACKAGES
     ===================================================== --}}
<section class="home-packages">

    <div class="container">

        <div class="home-section-heading">

            <span class="section-eyebrow">
                Paket Pilihan
            </span>

            <h2>
                Paket Favorit Pelanggan
            </h2>

            <p>
                Pilihan paket untuk berbagai kebutuhan fotografi.
            </p>

        </div>


        <div class="packages-grid">

            @foreach ($packages as $package)

            <article
                class="package-card {{ $loop->iteration === 2 ? 'featured' : '' }}">

                @if ($loop->iteration === 2)

                <span class="package-popular">
                    Pilihan Populer
                </span>

                @endif


                <span class="package-service">
                    {{ $package->service->name }}
                </span>


                <h3>
                    {{ $package->name }}
                </h3>


                <div class="package-price">

                    Rp{{ number_format($package->price, 0, ',', '.') }}

                    <small>
                        / sesi
                    </small>

                </div>


                <div class="package-meta">

                    <span>
                        {{ $package->duration_minutes }} menit
                    </span>

                    @if ($package->max_people)

                    <span>
                        Maks.
                        {{ $package->max_people }}
                        orang
                    </span>

                    @endif

                </div>


                @if ($package->package_includes)

                <div class="package-includes">

                    <strong>
                        Yang Anda Dapatkan:
                    </strong>

                    <p>
                        {{ $package->package_includes }}
                    </p>

                </div>

                @endif


                <a href="{{ route('packages.show', $package->id) }}"
                    class="package-button">

                    Lihat Detail

                </a>

            </article>

            @endforeach

        </div>


        <div class="section-action">

            <a href="{{ route('packages.index') }}" class="btn btn-outline">

                Lihat Semua Paket

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</section>



{{-- =====================================================
     BRANCHES
     ===================================================== --}}
<section class="home-branches">

    <div class="container">

        <div class="section-header-row">

            <div>

                <span class="section-eyebrow">
                    Cabang Aura Studio
                </span>

                <h2>
                    Temukan Studio Terdekat
                </h2>

            </div>


            {{-- Nanti diarahkan ke halaman Qila --}}
            <a href="#" class="section-text-link">

                Lihat Semua Cabang

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        <div class="branches-grid">

            @foreach ($branches as $branch)

            <article class="branch-card">

                <div class="branch-card-heading">

                    <span class="branch-icon">
                        <i class="bi bi-building"></i>
                    </span>

                    <span class="branch-status">
                        Buka
                    </span>

                </div>


                <h3>
                    {{ $branch->name }}
                </h3>


                <p class="branch-address">
                    {{ $branch->address }}
                </p>


                <div class="branch-info">

                    @if ($branch->phone)

                    <span>
                        <i class="bi bi-telephone"></i>
                        {{ $branch->phone }}
                    </span>

                    @endif


                    <span>
                        <i class="bi bi-clock"></i>

                        {{ $branch->opening_time }}
                        -
                        {{ $branch->closing_time }}
                    </span>

                </div>


                <div class="branch-actions">

                    @if ($branch->maps_url)

                    <a href="{{ $branch->maps_url }}"
                        target="_blank"
                        rel="noopener"
                        class="branch-location-link">

                        <i class="bi bi-geo-alt"></i>
                        Lihat di Peta

                    </a>

                    @endif


                    <a href="{{ route('branches.show', $branch->id) }}"
                        class="branch-detail">

                        Lihat Cabang

                    </a>

                </div>

            </article>

            @endforeach

        </div>

    </div>

</section>



{{-- =====================================================
     GALLERY
     ===================================================== --}}
<section class="home-gallery">

    <div class="container">

        <div class="home-section-heading">

            <span class="section-eyebrow">
                Galeri
            </span>

            <h2>
                Cerita dalam Setiap Foto
            </h2>

            <p>
                Lihat berbagai momen yang telah kami abadikan.
            </p>

        </div>


        @if ($galleries->isNotEmpty())

        <div class="gallery-grid">

            @foreach ($galleries as $gallery)

            <article class="gallery-item">

                <img
                    src="{{ asset('storage/' . $gallery->image) }}"
                    alt="{{ $gallery->title }}"
                    loading="lazy">

                <div class="gallery-overlay">

                    <span>
                        {{ $gallery->title }}
                    </span>

                </div>

            </article>

            @endforeach

        </div>

        @else

        <div class="gallery-empty">

            <i class="bi bi-images"></i>

            <h3>
                Galeri Segera Hadir
            </h3>

            <p>
                Koleksi hasil fotografi Aura Studio
                akan ditampilkan di sini.
            </p>

        </div>

        @endif

    </div>

</section>



{{-- =====================================================
     WHY AURA
     ===================================================== --}}
@php
$benefits = [
['icon' => 'bi-camera', 'title' => 'Fotografer Profesional', 'text' => 'Tim fotografer berpengalaman untuk berbagai kebutuhan foto.'],
['icon' => 'bi-box-seam', 'title' => 'Banyak Pilihan Paket', 'text' => 'Pilihan paket fleksibel sesuai kebutuhan dan anggaran Anda.'],
['icon' => 'bi-calendar-check', 'title' => 'Jadwal Fleksibel', 'text' => 'Tentukan tanggal dan waktu fotografi yang paling nyaman.'],
['icon' => 'bi-geo-alt', 'title' => 'Tersedia di Berbagai Cabang', 'text' => 'Pilih Aura Studio yang paling dekat dengan lokasi Anda.'],
];
@endphp

<section class="home-benefits">

    <div class="container">

        <div class="home-section-heading">

            <span class="section-eyebrow">
                Keunggulan
            </span>

            <h2>
                Mengapa Memilih Aura Studio?
            </h2>

            <p>
                Pengalaman fotografi yang kami bangun
                bukan hanya tentang hasil akhir.
            </p>

        </div>


        <div class="benefits-grid">

            @foreach ($benefits as $benefit)

            <article class="benefit-card">

                <i class="bi {{ $benefit['icon'] }}"></i>

                <h3>
                    {{ $benefit['title'] }}
                </h3>

                <p>
                    {{ $benefit['text'] }}
                </p>

            </article>

            @endforeach

        </div>

    </div>

</section>



{{-- =====================================================
     TESTIMONIAL
     ===================================================== --}}

@if ($testimonials->isNotEmpty())

<section class="home-testimonials">

    <div class="container">

        <div class="home-section-heading">

            <span class="section-eyebrow">
                Testimoni
            </span>

            <h2>
                Cerita dari Pelanggan Kami
            </h2>

            <p>
                Pengalaman pelanggan setelah
                menikmati layanan Aura Studio.
            </p>

        </div>


        <div class="testimonials-grid">

            @foreach ($testimonials as $testimonial)

            <article class="testimonial-card">

                <div class="testimonial-stars"
                    role="img"
                    aria-label="Rating {{ $testimonial->rating }} dari 5">

                    @for ($i = 1; $i <= 5; $i++)

                        <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }}"></i>

                        @endfor

                </div>


                <p>
                    {{ $testimonial->comment }}
                </p>


                <strong>

                    {{ $testimonial->booking->user->name ?? 'Pelanggan Aura Studio' }}

                </strong>

            </article>

            @endforeach

        </div>

    </div>

</section>

@endif



{{-- =====================================================
     FINAL CTA
     ===================================================== --}}
<section class="home-final-cta">

    <div class="container">

        <div class="final-cta-box">

            <span class="final-cta-label">
                Reservasi Sekarang
            </span>


            <h2>
                Siap Mengabadikan
                Momen Terbaik Anda?
            </h2>


            <p>
                Pilih layanan, cabang, paket,
                dan jadwal yang sesuai dengan kebutuhan Anda.
            </p>


            <div class="final-cta-actions">

                {{-- Booking bagian Abi --}}
                <a href="#" class="btn btn-primary">
                    Booking Sekarang
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="{{ route('services.index') }}" class="btn btn-outline-light">

                    Lihat Layanan Kami

                </a>

            </div>

        </div>

    </div>

</section>


@endsection