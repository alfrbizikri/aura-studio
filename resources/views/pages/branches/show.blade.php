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


            {{-- CONTENT --}}
            <div class="branch-detail-content reveal-left">

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

                    {{-- PHONE --}}
                    @if ($branch->phone)

                        <div class="branch-info-item">

                            <div class="branch-info-icon">

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


                    {{-- HOURS --}}
                    <div class="branch-info-item">

                        <div class="branch-info-icon">

                            <i class="bi bi-clock"></i>

                        </div>

                        <div>

                            <span>
                                Jam Operasional
                            </span>

                            <strong>

                                {{ \Carbon\Carbon::parse(
                                    $branch->opening_time
                                )->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse(
                                    $branch->closing_time
                                )->format('H:i') }}

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

                            Lihat Google Maps

                        </a>

                    @endif


                    <a
                        href="{{ route('branches.index') }}"
                        class="branch-secondary-action"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Semua Cabang

                    </a>

                </div>

            </div>



            {{-- VISUAL --}}
            <div class="branch-detail-visual reveal-right">

                <div class="branch-detail-circle"></div>

                <div class="branch-detail-building">

                    <i class="bi bi-building"></i>

                    <span>
                        Aura Studio
                    </span>

                    <strong>
                        {{ $branch->name }}
                    </strong>

                </div>


                <div class="branch-active-badge">

                    <span></span>

                    Cabang Aktif

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     LAYANAN CABANG
     ===================================================== --}}
<section class="branch-services-section">

    <div class="container">

        <div class="branch-section-heading reveal">

            <div>

                <div class="section-kicker">

                    <span></span>

                    Layanan

                </div>

                <h2>
                    Layanan di Cabang Ini
                </h2>

            </div>


            <p>
                Pilih layanan fotografi yang tersedia
                di {{ $branch->name }}.
            </p>

        </div>



        <div class="branch-services-grid">

            @forelse ($branch->services as $service)

                <article class="branch-service-card reveal">

                    <div class="branch-service-icon">

                        <i class="bi bi-camera"></i>

                    </div>


                    <div class="branch-service-content">

                        <span>
                            Layanan Aura Studio
                        </span>

                        <h3>
                            {{ $service->name }}
                        </h3>


                        @if ($service->description)

                            <p>
                                {{ \Illuminate\Support\Str::limit(
                                    $service->description,
                                    120
                                ) }}
                            </p>

                        @endif


                        <a
                            href="{{ route(
                                'services.show',
                                $service->slug
                            ) }}"
                        >

                            Lihat Layanan

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </article>


            @empty

                <div class="branch-empty-state">

                    <i class="bi bi-camera"></i>

                    <h3>
                        Belum Ada Layanan
                    </h3>

                    <p>
                        Belum ada layanan aktif
                        pada cabang ini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>



{{-- =====================================================
     FOTOGRAFER CABANG
     ===================================================== --}}
<section class="branch-photographers-section">

    <div class="container">

        <div class="branch-section-heading reveal">

            <div>

                <div class="section-kicker">

                    <span></span>

                    Fotografer

                </div>

                <h2>
                    Fotografer di Cabang Ini
                </h2>

            </div>


            <div>

                <p>
                    Kenali fotografer yang tersedia
                    di {{ $branch->name }}.
                </p>

                <a
                    href="{{ route(
                        'photographers.index',
                        ['branch' => $branch->id]
                    ) }}"
                    class="branch-view-all"
                >

                    Lihat Semua Fotografer

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>



        <div class="branch-photographers-grid">

            @forelse ($branch->photographers as $photographer)

                <article class="branch-photographer-card reveal">


                    {{-- PHOTO --}}
                    <div class="branch-photographer-photo">

                        @if ($photographer->photo)

                            <img
                                src="{{ asset(
                                    'storage/' . $photographer->photo
                                ) }}"
                                alt="{{ $photographer->name }}"
                            >

                        @else

                            <div class="branch-photographer-placeholder">

                                <i class="bi bi-person"></i>

                            </div>

                        @endif


                        <div class="branch-photographer-status">

                            <span></span>

                            Tersedia

                        </div>

                    </div>



                    {{-- CONTENT --}}
                    <div class="branch-photographer-content">

                        <span class="branch-photographer-specialization">

                            {{ $photographer->specialization
                                ?? 'Fotografer' }}

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

                        @else

                            <p>
                                Fotografer Aura Studio yang siap
                                membantu mengabadikan momen Anda.
                            </p>

                        @endif


                        {{-- INI LINK KE DETAIL FOTOGRAFER --}}
                        <a
                            href="{{ route(
                                'photographers.show',
                                $photographer->id
                            ) }}"
                            class="branch-photographer-detail"
                        >

                            Lihat Profil

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </article>


            @empty

                <div class="branch-empty-state">

                    <i class="bi bi-person"></i>

                    <h3>
                        Belum Ada Fotografer
                    </h3>

                    <p>
                        Belum ada fotografer aktif
                        pada cabang ini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>



{{-- =====================================================
     STUDIO ROOM
     ===================================================== --}}
<section class="branch-rooms-section">

    <div class="container">

        <div class="branch-section-heading reveal">

            <div>

                <div class="section-kicker">

                    <span></span>

                    Studio

                </div>

                <h2>
                    Ruang Studio
                </h2>

            </div>


            <p>
                Ruang studio yang tersedia
                di {{ $branch->name }}.
            </p>

        </div>



        <div class="branch-rooms-grid">

            @forelse ($branch->studioRooms as $room)

                <article class="branch-room-card reveal">

                    <div class="branch-room-number">

                        {{ str_pad(
                            $loop->iteration,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ) }}

                    </div>


                    <div class="branch-room-icon">

                        <i class="bi bi-door-open"></i>

                    </div>


                    <div class="branch-room-content">

                        <span>
                            Ruang Studio
                        </span>

                        <h3>
                            {{ $room->name }}
                        </h3>


                        @if ($room->description)

                            <p>
                                {{ $room->description }}
                            </p>

                        @endif


                        <div class="branch-room-status">

                            <span></span>

                            Tersedia

                        </div>

                    </div>

                </article>


            @empty

                <div class="branch-empty-state">

                    <i class="bi bi-door-open"></i>

                    <h3>
                        Belum Ada Ruang Studio
                    </h3>

                    <p>
                        Informasi ruang studio
                        belum tersedia.
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
                Siap Membuat
                Momen Berkesan?
            </h2>


            <p>
                Pilih layanan dan paket fotografi
                yang sesuai dengan kebutuhan Anda.
            </p>


            <div class="branch-detail-cta-actions">

                <a
                    href="{{ route('services.index') }}"
                    class="branch-detail-cta-primary"
                >

                    Lihat Layanan

                </a>


                <a
                    href="{{ route('packages.index') }}"
                    class="branch-detail-cta-secondary"
                >

                    Lihat Paket

                </a>

            </div>

        </div>

    </div>

</section>


@endsection