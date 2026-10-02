@extends('layouts.app')

@section('title', $photographer->name . ' - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/photographers.css') }}">
@endpush

@section('content')


{{-- =====================================================
     BREADCRUMB
     ===================================================== --}}
<section class="photographer-detail-top">

    <div class="container">

        <div class="photographer-breadcrumb">

            <a href="{{ route('home') }}">
                Beranda
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('photographers.index') }}">
                Fotografer
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                {{ $photographer->name }}
            </span>

        </div>

    </div>

</section>



{{-- =====================================================
     HERO DETAIL
     ===================================================== --}}
<section class="photographer-detail-hero">

    <div class="container">

        <div class="photographer-detail-grid">

            {{-- PHOTO --}}
            <div class="photographer-detail-photo-wrap reveal-left">

                <div class="photographer-detail-circle"></div>

                <div class="photographer-detail-photo">

                    @if ($photographer->photo)

                        <img
                            src="{{ asset('storage/' . $photographer->photo) }}"
                            alt="{{ $photographer->name }}"
                        >

                    @else

                        <div class="photographer-detail-placeholder">

                            <i class="bi bi-person"></i>

                            <span>
                                Aura Studio
                            </span>

                        </div>

                    @endif

                </div>


                <div class="photographer-detail-status">

                    <span></span>

                    Aktif

                </div>

            </div>



            {{-- CONTENT --}}
            <div class="photographer-detail-content reveal-right">

                <div class="section-kicker">

                    <span></span>

                    Fotografer Aura Studio

                </div>


                <h1>
                    {{ $photographer->name }}
                </h1>


                <span class="photographer-detail-specialization">

                    {{ $photographer->specialization ?? 'Fotografer' }}

                </span>


                <p class="photographer-detail-description">

                    {{ $photographer->description
                        ?? 'Fotografer Aura Studio yang siap membantu mengabadikan momen Anda.' }}

                </p>


                <div class="photographer-detail-divider"></div>


                <div class="photographer-detail-meta">

                    <div class="photographer-meta-item">

                        <div class="photographer-meta-icon">

                            <i class="bi bi-building"></i>

                        </div>

                        <div>

                            <span>
                                Cabang
                            </span>

                            <strong>
                                {{ $photographer->branch->name }}
                            </strong>

                        </div>

                    </div>


                    <div class="photographer-meta-item">

                        <div class="photographer-meta-icon">

                            <i class="bi bi-camera"></i>

                        </div>

                        <div>

                            <span>
                                Spesialisasi
                            </span>

                            <strong>
                                {{ $photographer->specialization ?? 'Fotografi' }}
                            </strong>

                        </div>

                    </div>

                </div>


                <div class="photographer-detail-actions">

                    <a
                        href="{{ route(
                            'branches.show',
                            $photographer->branch->id
                        ) }}"
                        class="photographer-primary-action"
                    >

                        <i class="bi bi-building"></i>

                        Lihat Cabang

                    </a>


                    <a
                        href="{{ route('photographers.index') }}"
                        class="photographer-secondary-action"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Semua Fotografer

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     ABOUT PHOTOGRAPHER
     ===================================================== --}}
<section class="photographer-about-section">

    <div class="container">

        <div class="photographer-about-grid">

            <div class="photographer-about-heading reveal">

                <div class="section-kicker">

                    <span></span>

                    Profil

                </div>

                <h2>
                    Tentang Fotografer
                </h2>

            </div>


            <div class="photographer-about-content reveal">

                @if ($photographer->description)

                    <p>
                        {{ $photographer->description }}
                    </p>

                @else

                    <p>
                        Informasi lengkap mengenai fotografer
                        ini belum tersedia.
                    </p>

                @endif


                <div class="photographer-about-info">

                    <div>

                        <span>
                            Nama
                        </span>

                        <strong>
                            {{ $photographer->name }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Spesialisasi
                        </span>

                        <strong>
                            {{ $photographer->specialization ?? 'Fotografi' }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Cabang
                        </span>

                        <strong>
                            {{ $photographer->branch->name }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     BRANCH INFORMATION
     ===================================================== --}}
<section class="photographer-branch-section">

    <div class="container">

        <div class="photographer-branch-card reveal">

            <div class="photographer-branch-icon">

                <i class="bi bi-building"></i>

            </div>


            <div class="photographer-branch-content">

                <span>
                    Cabang Fotografer
                </span>

                <h2>
                    {{ $photographer->branch->name }}
                </h2>

                <p>

                    <i class="bi bi-geo-alt"></i>

                    {{ $photographer->branch->address }}

                </p>


                <div class="photographer-branch-meta">

                    @if ($photographer->branch->phone)

                        <span>

                            <i class="bi bi-telephone"></i>

                            {{ $photographer->branch->phone }}

                        </span>

                    @endif


                    <span>

                        <i class="bi bi-clock"></i>

                        {{ \Carbon\Carbon::parse(
                            $photographer->branch->opening_time
                        )->format('H:i') }}

                        -

                        {{ \Carbon\Carbon::parse(
                            $photographer->branch->closing_time
                        )->format('H:i') }}

                    </span>

                </div>

            </div>


            <div class="photographer-branch-action">

                <a
                    href="{{ route(
                        'branches.show',
                        $photographer->branch->id
                    ) }}"
                >

                    Lihat Detail Cabang

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     CTA
     ===================================================== --}}
<section class="photographer-detail-cta">

    <div class="container">

        <div class="photographer-detail-cta-box reveal">

            <span>
                Aura Studio
            </span>


            <h2>
                Siap Merencanakan
                Sesi Fotomu?
            </h2>


            <p>
                Pilih layanan dan paket sesuai kebutuhan,
                kemudian tentukan fotografer dan jadwal
                pada proses booking.
            </p>


            <div class="photographer-detail-cta-actions">

                <a
                    href="{{ route('services.index') }}"
                    class="photographer-detail-cta-primary"
                >

                    Lihat Layanan

                </a>


                <a
                    href="{{ route('packages.index') }}"
                    class="photographer-detail-cta-secondary"
                >

                    Lihat Paket

                </a>

            </div>

        </div>

    </div>

</section>


@endsection