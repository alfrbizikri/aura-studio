@extends('layouts.app')

@section('title', 'Galeri - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/gallery.css') }}"
    >
@endpush


@section('content')


{{-- =====================================================
     HERO
     ===================================================== --}}
<section class="gallery-hero">

    <div class="container">

        <div class="gallery-hero-content">

            <span class="gallery-kicker">
                GALERI AURA STUDIO
            </span>

            <h1>
                Cerita yang Kami
                <em>Abadikan.</em>
            </h1>

            <p>
                Jelajahi hasil karya Aura Studio dari
                berbagai layanan fotografi dan momen
                yang telah kami abadikan.
            </p>

        </div>

    </div>

</section>



{{-- =====================================================
     GALLERY
     ===================================================== --}}
<section class="gallery-section">

    <div class="container">


        {{-- HEADING --}}
        <div class="gallery-heading">

            <div>

                <span class="gallery-small-label">
                    PORTOFOLIO
                </span>

                <h2>
                    Hasil Karya Kami
                </h2>

            </div>


            <p>
                Pilih kategori layanan untuk melihat
                hasil fotografi yang sesuai.
            </p>

        </div>



        {{-- =================================================
             FILTER
             ================================================= --}}
        <div class="gallery-filters">

            <a
                href="{{ route('gallery.index') }}"
                class="gallery-filter-btn
                {{ !request('service') ? 'active' : '' }}"
            >
                Semua
            </a>


            @foreach ($services as $service)

                <a
                    href="{{ route(
                        'gallery.index',
                        ['service' => $service->id]
                    ) }}"
                    class="gallery-filter-btn
                    {{ request('service') == $service->id
                        ? 'active'
                        : '' }}"
                >
                    {{ $service->name }}
                </a>

            @endforeach

        </div>



        {{-- =================================================
             GALLERY CARDS
             ================================================= --}}
        <div class="gallery-grid">

            @forelse ($galleries as $gallery)

                <article class="gallery-card">

                    <div class="gallery-image">

                        @if (
                            $gallery->image &&
                            \Illuminate\Support\Facades\Storage::disk('public')
                                ->exists($gallery->image)
                        )

                            <img
                                src="{{ asset(
                                    'storage/' . $gallery->image
                                ) }}"
                                alt="{{ $gallery->title }}"
                            >

                        @else

                            <div class="gallery-placeholder">

                                <i class="bi bi-image"></i>

                                <span>
                                    Aura Studio
                                </span>

                            </div>

                        @endif


                        <div class="gallery-overlay">

                            <span>
                                {{ $gallery->service->name }}
                            </span>

                        </div>

                    </div>


                    <div class="gallery-card-content">

                        <span class="gallery-service">

                            <i class="bi bi-camera"></i>

                            {{ $gallery->service->name }}

                        </span>


                        <h3>
                            {{ $gallery->title }}
                        </h3>


                        @if ($gallery->description)

                            <p>
                                {{ \Illuminate\Support\Str::limit(
                                    $gallery->description,
                                    120
                                ) }}
                            </p>

                        @endif

                    </div>

                </article>


            @empty

                <div class="gallery-empty">

                    <div class="gallery-empty-icon">

                        <i class="bi bi-images"></i>

                    </div>

                    <h3>
                        Galeri Belum Tersedia
                    </h3>

                    <p>
                        Belum ada foto galeri yang
                        dipublikasikan pada kategori ini.
                    </p>


                    @if (request('service'))

                        <a href="{{ route('gallery.index') }}">
                            Lihat Semua Galeri
                        </a>

                    @endif

                </div>

            @endforelse

        </div>

    </div>

</section>



{{-- =====================================================
     CTA
     ===================================================== --}}
<section class="gallery-cta">

    <div class="container">

        <div class="gallery-cta-box">

            <span>
                Aura Studio
            </span>

            <h2>
                Buat Ceritamu
                Menjadi Bagian Berikutnya.
            </h2>

            <p>
                Temukan layanan dan paket fotografi
                yang sesuai dengan momen Anda.
            </p>

            <div class="gallery-cta-actions">

                <a
                    href="{{ route('services.index') }}"
                    class="gallery-cta-primary"
                >
                    Lihat Layanan
                </a>

                <a
                    href="{{ route('packages.index') }}"
                    class="gallery-cta-secondary"
                >
                    Lihat Paket
                </a>

            </div>

        </div>

    </div>

</section>


@endsection