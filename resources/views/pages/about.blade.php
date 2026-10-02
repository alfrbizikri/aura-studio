@extends('layouts.app')

@section('title', 'Tentang Kami - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/about.css') }}"
    >
@endpush

@section('content')


{{-- =====================================================
     HERO
     ===================================================== --}}
<section class="about-hero">

    <div class="container">

        <div class="about-hero-grid">

            <div class="about-hero-content">

                <span class="about-kicker">
                    TENTANG AURA STUDIO
                </span>

                <h1>
                    Mengabadikan Cerita,
                    <em>Menyimpan Kenangan.</em>
                </h1>

                <p>
                    Aura Studio hadir untuk membantu
                    mengabadikan berbagai momen melalui
                    layanan fotografi yang nyaman,
                    profesional, dan mudah diakses.
                </p>

                <div class="about-hero-actions">

                    <a
                        href="{{ route('services.index') }}"
                        class="about-primary-btn"
                    >
                        Lihat Layanan
                    </a>

                    <a
                        href="#contact"
                        class="about-secondary-btn"
                    >
                        Hubungi Kami
                    </a>

                </div>

            </div>


            <div class="about-hero-visual">

                <div class="about-visual-circle"></div>

                <div class="about-visual-card">

                    <i class="bi bi-camera"></i>

                    <span>
                        Aura Studio
                    </span>

                    <strong>
                        Every Moment
                        Has a Story
                    </strong>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     ABOUT
     ===================================================== --}}
<section class="about-story">

    <div class="container">

        <div class="about-story-grid">

            <div>

                <span class="about-small-label">
                    SIAPA KAMI
                </span>

                <h2>
                    Fotografi untuk
                    Setiap Cerita.
                </h2>

            </div>


            <div class="about-story-content">

                <p>
                    Aura Studio merupakan studio fotografi
                    yang menyediakan berbagai pilihan
                    layanan untuk kebutuhan personal,
                    keluarga, maupun kegiatan di luar
                    studio.
                </p>

                <p>
                    Melalui pilihan layanan, paket,
                    fotografer, cabang, serta jadwal yang
                    tersedia, pelanggan dapat menentukan
                    sesi fotografi sesuai kebutuhannya.
                </p>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     VALUES
     ===================================================== --}}
<section class="about-values">

    <div class="container">

        <div class="about-section-heading">

            <span class="about-small-label">
                AURA STUDIO
            </span>

            <h2>
                Pengalaman yang Kami Utamakan
            </h2>

        </div>


        <div class="about-values-grid">

            <article class="about-value-card">

                <div class="about-value-icon">
                    <i class="bi bi-camera"></i>
                </div>

                <h3>
                    Pilihan Layanan
                </h3>

                <p>
                    Beragam pilihan layanan fotografi
                    dapat disesuaikan dengan kebutuhan
                    sesi pelanggan.
                </p>

            </article>


            <article class="about-value-card">

                <div class="about-value-icon">
                    <i class="bi bi-person"></i>
                </div>

                <h3>
                    Fotografer
                </h3>

                <p>
                    Pelanggan dapat mengenal fotografer
                    yang tersedia pada masing-masing
                    cabang Aura Studio.
                </p>

            </article>


            <article class="about-value-card">

                <div class="about-value-icon">
                    <i class="bi bi-building"></i>
                </div>

                <h3>
                    Pilihan Cabang
                </h3>

                <p>
                    Aura Studio menyediakan beberapa
                    lokasi cabang yang dapat dipilih
                    sesuai kebutuhan pelanggan.
                </p>

            </article>

        </div>

    </div>

</section>



{{-- =====================================================
     CONTACT / BRANCHES
     ===================================================== --}}
<section
    class="about-contact"
    id="contact"
>

    <div class="container">

        <div class="about-contact-heading">

            <div>

                <span class="about-small-label">
                    HUBUNGI KAMI
                </span>

                <h2>
                    Temukan Aura Studio
                    di Dekatmu.
                </h2>

            </div>

            <p>
                Hubungi cabang Aura Studio untuk
                mendapatkan informasi lebih lanjut
                mengenai layanan dan sesi fotografi.
            </p>

        </div>


        <div class="about-branches-grid">

            @forelse ($branches as $branch)

                <article class="about-branch-card">

                    <div class="about-branch-icon">

                        <i class="bi bi-building"></i>

                    </div>


                    <span class="about-branch-status">

                        <span></span>

                        Cabang Aktif

                    </span>


                    <h3>
                        {{ $branch->name }}
                    </h3>


                    <p class="about-branch-address">

                        <i class="bi bi-geo-alt"></i>

                        {{ $branch->address }}

                    </p>


                    <div class="about-branch-info">

                        @if ($branch->phone)

                            <div>

                                <i class="bi bi-telephone"></i>

                                <span>
                                    {{ $branch->phone }}
                                </span>

                            </div>

                        @endif


                        <div>

                            <i class="bi bi-clock"></i>

                            <span>

                                {{ \Carbon\Carbon::parse(
                                    $branch->opening_time
                                )->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse(
                                    $branch->closing_time
                                )->format('H:i') }}

                            </span>

                        </div>

                    </div>


                    <div class="about-branch-actions">

                        <a
                            href="{{ route(
                                'branches.show',
                                $branch->id
                            ) }}"
                        >
                            Detail Cabang

                            <i class="bi bi-arrow-right"></i>
                        </a>


                        @if ($branch->maps_url)

                            <a
                                href="{{ $branch->maps_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                Maps
                            </a>

                        @endif

                    </div>

                </article>

            @empty

                <div class="about-empty">

                    <i class="bi bi-building"></i>

                    <h3>
                        Cabang Belum Tersedia
                    </h3>

                </div>

            @endforelse

        </div>

    </div>

</section>



{{-- =====================================================
     CTA
     ===================================================== --}}
<section class="about-cta">

    <div class="container">

        <div class="about-cta-box">

            <span>
                Aura Studio
            </span>

            <h2>
                Siap Mengabadikan
                Momenmu?
            </h2>

            <p>
                Jelajahi layanan dan paket yang tersedia
                untuk memulai sesi fotografi bersama
                Aura Studio.
            </p>


            <div class="about-cta-actions">

                <a
                    href="{{ route('services.index') }}"
                    class="about-cta-primary"
                >
                    Lihat Layanan
                </a>

                <a
                    href="{{ route('gallery.index') }}"
                    class="about-cta-secondary"
                >
                    Lihat Galeri
                </a>

            </div>

        </div>

    </div>

</section>


@endsection