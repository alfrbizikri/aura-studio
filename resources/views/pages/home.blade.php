@extends('layouts.app')

@section('title', 'Aura Studio - Beranda')

@section('content')

{{-- =====================================================
     HERO SECTION
     ===================================================== --}}
<section class="home-hero">
    <div class="container">

        <div class="home-hero-content">
            <span class="section-eyebrow">
                Capture Your Moment
            </span>

            <h1>
                Setiap Momen Punya Cerita.
                Kami Membantu Mengabadikannya.
            </h1>

            <p>
                Temukan pengalaman fotografi yang nyaman,
                profesional, dan sesuai dengan momen spesialmu
                bersama Aura Studio.
            </p>

            <div class="home-hero-actions">

                <a href="#" class="btn btn-primary">
                    Booking Sekarang
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="{{ route('services.index') }}"
                   class="btn btn-outline">
                    Lihat Layanan
                </a>

            </div>
        </div>

    </div>
</section>


{{-- =====================================================
     SERVICES SECTION
     ===================================================== --}}
<section class="home-services">
    <div class="container">

        <div class="section-heading">

            <span class="section-eyebrow">
                Layanan Kami
            </span>

            <h2>
                Pilih Pengalaman Fotografi Sesuai Kebutuhanmu
            </h2>

            <p>
                Aura Studio menyediakan tiga layanan fotografi
                untuk berbagai kebutuhan dan momen.
            </p>

        </div>


        <div class="services-grid">

            @foreach ($services as $service)

                <article class="service-card">

                    <div class="service-card-content">

                        <span class="service-card-label">
                            Layanan
                        </span>

                        <h3>
                            {{ $service->name }}
                        </h3>

                        <p>
                            {{ $service->description }}
                        </p>

                        <a href="{{ route('services.show', $service->slug) }}"
                           class="service-card-link">

                            Lihat Detail
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    </div>
</section>


{{-- =====================================================
     FEATURED PACKAGES
     ===================================================== --}}
<section class="home-packages">
    <div class="container">

        <div class="section-heading">

            <span class="section-eyebrow">
                Paket Pilihan
            </span>

            <h2>
                Paket Favorit untuk Momen Spesialmu
            </h2>

        </div>


        <div class="packages-grid">

            @foreach ($packages as $package)

                <article class="package-card">

                    <div class="package-card-content">

                        <span class="package-card-label">
                            Paket
                        </span>

                        <h3>
                            {{ $package->name }}
                        </h3>

                        <p>
                            {{ $package->description }}
                        </p>

                        <div class="package-card-meta">

                            <span>
                                <i class="bi bi-clock"></i>
                                {{ $package->duration_minutes }} menit
                            </span>

                            @if ($package->max_people)

                                <span>
                                    <i class="bi bi-people"></i>
                                    Maks. {{ $package->max_people }} orang
                                </span>

                            @endif

                        </div>


                        <div class="package-card-footer">

                            <strong>
                                Rp{{ number_format($package->price, 0, ',', '.') }}
                            </strong>

                            <a href="{{ route('packages.show', $package->id) }}"
                               class="package-card-link">

                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>

                            </a>

                        </div>

                    </div>

                </article>

            @endforeach

        </div>

    </div>
</section>


{{-- =====================================================
     BRANCHES
     ===================================================== --}}
<section class="home-branches">
    <div class="container">

        <div class="section-heading">

            <span class="section-eyebrow">
                Cabang Aura Studio
            </span>

            <h2>
                Temukan Studio Terdekat
            </h2>

        </div>


        <div class="branches-grid">

            @foreach ($branches as $branch)

                <article class="branch-card">

                    <div class="branch-card-content">

                        <span class="branch-card-label">
                            Cabang
                        </span>

                        <h3>
                            {{ $branch->name }}
                        </h3>

                        <p>
                            {{ $branch->address }}
                        </p>


                        <div class="branch-card-meta">

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


                        {{-- Detail cabang dikerjakan oleh Qila --}}
                        <a href="#" class="branch-card-link">

                            Lihat Detail Cabang
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    </div>
</section>


{{-- =====================================================
     GALLERY PREVIEW
     ===================================================== --}}
<section class="home-gallery">
    <div class="container">

        <div class="section-heading">

            <span class="section-eyebrow">
                Galeri
            </span>

            <h2>
                Cerita yang Kami Abadikan
            </h2>

        </div>


        <div class="gallery-grid">

            @forelse ($galleries as $gallery)

                <article class="gallery-card">

                    <div class="gallery-card-image">

                        <img
                            src="{{ asset('storage/' . $gallery->image) }}"
                            alt="{{ $gallery->title }}"
                        >

                    </div>


                    <div class="gallery-card-content">

                        <h3>
                            {{ $gallery->title }}
                        </h3>


                        @if ($gallery->description)

                            <p>
                                {{ $gallery->description }}
                            </p>

                        @endif

                    </div>

                </article>

            @empty

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

            @endforelse

        </div>

    </div>
</section>


{{-- =====================================================
     BOOKING CTA
     ===================================================== --}}
<section class="home-cta">
    <div class="container">

        <div class="home-cta-box">

            <div>

                <span class="section-eyebrow">
                    Siap Mengabadikan Momenmu?
                </span>

                <h2>
                    Jadwalkan Sesi Fotomu Bersama Aura Studio
                </h2>

            </div>


            {{-- Alur booking dikerjakan oleh Abi --}}
            <a href="#" class="btn btn-primary">

                Mulai Booking
                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>
</section>

@endsection