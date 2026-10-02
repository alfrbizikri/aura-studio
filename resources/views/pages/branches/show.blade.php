@extends('layouts.app')

@section('title', $branch->name . ' - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/branches.css') }}">
@endpush

@section('content')


{{-- =====================================================
     BREADCRUMB
     ===================================================== --}}
<section class="branch-detail-top">

    <div class="container">

        <div class="branch-breadcrumb">

            <a href="{{ route('home') }}">
                Beranda
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('branches.index') }}">
                Cabang
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                {{ $branch->name }}
            </span>

        </div>

    </div>

</section>



{{-- =====================================================
     HERO DETAIL CABANG
     ===================================================== --}}
<section class="branch-detail-hero">

    <div class="container">

        <div class="branch-detail-grid">

            {{-- LEFT --}}
            <div class="branch-detail-copy reveal-left">

                <div class="section-kicker">

                    <span></span>

                    Cabang Aura Studio

                </div>


                <h1>
                    {{ $branch->name }}
                </h1>


                <p class="branch-detail-address">

                    <i class="bi bi-geo-alt"></i>

                    {{ $branch->address }}

                </p>


                <div class="branch-detail-info">

                    @if ($branch->phone)

                        <div class="branch-detail-info-item">

                            <div class="branch-detail-info-icon">
                                <i class="bi bi-telephone"></i>
                            </div>

                            <div>

                                <span>
                                    Telepon
                                </span>

                                <strong>
                                    {{ $branch->phone }}
                                </strong>

                            </div>

                        </div>

                    @endif


                    <div class="branch-detail-info-item">

                        <div class="branch-detail-info-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div>

                            <span>
                                Jam Operasional
                            </span>

                            <strong>

                                {{ \Carbon\Carbon::parse($branch->opening_time)->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse($branch->closing_time)->format('H:i') }}

                            </strong>

                        </div>

                    </div>

                </div>


                <div class="branch-detail-actions">

                    @if ($branch->maps_url)

                        <a
                            href="{{ $branch->maps_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="branch-primary-action"
                        >

                            <i class="bi bi-map"></i>

                            Lihat Lokasi

                        </a>

                    @endif


                    <a
                        href="{{ route('branches.index') }}"
                        class="branch-secondary-action"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Cabang Lain

                    </a>

                </div>

            </div>


            {{-- RIGHT --}}
            <div class="branch-detail-visual reveal-right">

                <div class="branch-detail-visual-circle"></div>

                <div class="branch-detail-building">

                    <div class="branch-building-icon">

                        <i class="bi bi-building"></i>

                    </div>


                    <span>
                        Aura Studio
                    </span>


                    <strong>
                        {{ $branch->name }}
                    </strong>

                </div>


                <div class="branch-detail-status">

                    <span></span>

                    Cabang Aktif

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     SERVICES
     ===================================================== --}}
<section class="branch-detail-section">

    <div class="container">

        <div class="branch-section-header reveal">

            <div>

                <div class="section-kicker">

                    <span></span>

                    Layanan

                </div>

                <h2>
                    Layanan yang Tersedia
                </h2>

            </div>


            <p>
                Pilih layanan fotografi yang tersedia
                di {{ $branch->name }}.
            </p>

        </div>


        <div class="branch-detail-services">

            @forelse ($branch->services as $service)

                <article
                    class="branch-service-card reveal
                    reveal-delay-{{ min($loop->iteration, 3) }}"
                >

                    <div class="branch-service-icon">

                        @if ($service->is_on_location)

                            <i class="bi bi-geo-alt"></i>

                        @elseif ($service->requires_photographer)

                            <i class="bi bi-camera"></i>

                        @else

                            <i class="bi bi-person-bounding-box"></i>

                        @endif

                    </div>


                    <h3>
                        {{ $service->name }}
                    </h3>


                    <p>
                        {{ $service->description }}
                    </p>


                    <a
                        href="{{ route('services.show', $service->slug) }}"
                    >

                        Lihat Layanan

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </article>

            @empty

                <div class="branch-detail-empty">

                    <i class="bi bi-camera"></i>

                    <p>
                        Belum ada layanan aktif di cabang ini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>



{{-- =====================================================
     PHOTOGRAPHERS
     ===================================================== --}}
<section class="branch-photographers-section">

    <div class="container">

        <div class="branch-section-header reveal">

            <div>

                <div class="section-kicker">

                    <span></span>

                    Fotografer

                </div>

                <h2>
                    Fotografer di Cabang Ini
                </h2>

            </div>


            <p>
                Kenali fotografer yang tersedia
                di {{ $branch->name }}.
            </p>

        </div>


        <div class="branch-photographers-grid">

            @forelse ($branch->photographers as $photographer)

                <article
                    class="branch-photographer-card reveal
                    reveal-delay-{{ min($loop->iteration, 3) }}"
                >

                    <div class="branch-photographer-photo">

                        @if ($photographer->photo)

                            <img
                                src="{{ asset('storage/' . $photographer->photo) }}"
                                alt="{{ $photographer->name }}"
                            >

                        @else

                            <div class="branch-photo-placeholder">

                                <i class="bi bi-person"></i>

                            </div>

                        @endif

                    </div>


                    <div class="branch-photographer-content">

                        <span>
                            {{ $photographer->specialization ?? 'Fotografer' }}
                        </span>

                        <h3>
                            {{ $photographer->name }}
                        </h3>


                        @if ($photographer->description)

                            <p>
                                {{ \Illuminate\Support\Str::limit(
                                    $photographer->description,
                                    110
                                ) }}
                            </p>

                        @endif

                    </div>

                </article>

            @empty

                <div class="branch-detail-empty">

                    <i class="bi bi-person"></i>

                    <p>
                        Belum ada fotografer aktif di cabang ini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>



{{-- =====================================================
     STUDIO ROOMS
     ===================================================== --}}
<section class="branch-rooms-section">

    <div class="container">

        <div class="branch-section-header reveal">

            <div>

                <div class="section-kicker">

                    <span></span>

                    Studio Room

                </div>

                <h2>
                    Ruang Studio
                </h2>

            </div>


            <p>
                Ruang studio yang tersedia untuk mendukung
                sesi fotografi Anda.
            </p>

        </div>


        <div class="branch-rooms-grid">

            @forelse ($branch->studioRooms as $room)

                <article
                    class="branch-room-card reveal
                    reveal-delay-{{ min($loop->iteration, 3) }}"
                >

                    <div class="branch-room-icon">

                        <i class="bi bi-door-open"></i>

                    </div>


                    <div>

                        <span>
                            Studio Room
                        </span>

                        <h3>
                            {{ $room->name }}
                        </h3>


                        @if ($room->description)

                            <p>
                                {{ $room->description }}
                            </p>

                        @endif

                    </div>

                </article>

            @empty

                <div class="branch-detail-empty">

                    <i class="bi bi-door-open"></i>

                    <p>
                        Belum ada ruang studio aktif.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>



{{-- =====================================================
     CTA
     ===================================================== --}}
<section class="branch-detail-cta">

    <div class="container">

        <div class="branch-detail-cta-box reveal">

            <span>
                Aura Studio
            </span>


            <h2>
                Sudah Menemukan Cabang
                yang Sesuai?
            </h2>


            <p>
                Pilih layanan dan paket yang sesuai,
                lalu lanjutkan proses booking.
            </p>


            <div class="branch-detail-cta-actions">

                <a
                    href="{{ route('services.index') }}"
                    class="branch-cta-primary"
                >

                    Lihat Layanan

                </a>


                <a
                    href="{{ route('packages.index') }}"
                    class="branch-cta-secondary"
                >

                    Lihat Paket

                </a>

            </div>

        </div>

    </div>

</section>


@endsection