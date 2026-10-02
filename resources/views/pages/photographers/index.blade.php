@extends('layouts.app')

@section('title', 'Fotografer - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/photographers.css') }}">
@endpush

@section('content')


{{-- =====================================================
     HERO
     ===================================================== --}}
<section class="photographers-hero">

    <div class="container">

        <div class="photographers-hero-grid">

            <div class="photographers-hero-copy reveal-left">

                <div class="section-kicker">

                    <span></span>

                    Fotografer Aura Studio

                </div>


                <h1>
                    Temukan Fotografer
                    <em>untuk Momenmu</em>
                </h1>


                <p>
                    Kenali fotografer Aura Studio dan pilih
                    fotografer sesuai lokasi cabang serta
                    kebutuhan sesi fotografi Anda.
                </p>

            </div>


            <div class="photographers-hero-visual reveal-right">

                <div class="photographer-hero-circle"></div>

                <div class="photographer-hero-card">

                    <i class="bi bi-camera"></i>

                    <span>
                        Aura Studio
                    </span>

                    <strong>
                        Creative Team
                    </strong>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =====================================================
     PHOTOGRAPHERS
     ===================================================== --}}
<section class="photographers-section">

    <div class="container">

        <div class="photographers-heading reveal">

            <div>

                <span class="photographers-count">

                    {{ $photographers->count() }}
                    Fotografer Tersedia

                </span>

                <h2>
                    Tim Fotografer Kami
                </h2>

            </div>


            <p>
                Pilih fotografer berdasarkan cabang
                yang paling sesuai dengan kebutuhan Anda.
            </p>

        </div>



        {{-- =================================================
             FILTER CABANG
             ================================================= --}}
        <div class="photographer-filters reveal">

            <a
                href="{{ route('photographers.index') }}"
                class="photographer-filter-btn
                {{ !request('branch') ? 'active' : '' }}"
            >

                Semua Cabang

            </a>


            @foreach ($branches as $branch)

                <a
                    href="{{ route(
                        'photographers.index',
                        ['branch' => $branch->id]
                    ) }}"
                    class="photographer-filter-btn
                    {{ request('branch') == $branch->id ? 'active' : '' }}"
                >

                    {{ $branch->name }}

                </a>

            @endforeach

        </div>



        {{-- =================================================
             CARDS
             ================================================= --}}
        <div class="photographers-grid">

            @forelse ($photographers as $photographer)

                <article
                    class="photographer-card reveal
                    reveal-delay-{{ (($loop->iteration - 1) % 3) + 1 }}"
                >

                    {{-- PHOTO --}}
                    <div class="photographer-photo">

                        @if ($photographer->photo)

                            <img
                                src="{{ asset(
                                    'storage/' . $photographer->photo
                                ) }}"
                                alt="{{ $photographer->name }}"
                            >

                        @else

                            <div class="photographer-placeholder">

                                <i class="bi bi-person"></i>

                            </div>

                        @endif


                        <span class="photographer-status">

                            <span></span>

                            Tersedia

                        </span>

                    </div>


                    {{-- CONTENT --}}
                    <div class="photographer-content">

                        <span class="photographer-branch">

                            <i class="bi bi-geo-alt"></i>

                            {{ $photographer->branch->name }}

                        </span>


                        <h3>
                            {{ $photographer->name }}
                        </h3>


                        <span class="photographer-specialization">

                            {{ $photographer->specialization
                                ?? 'Fotografer' }}

                        </span>


                        @if ($photographer->description)

                            <p>

                                {{ \Illuminate\Support\Str::limit(
                                    $photographer->description,
                                    125
                                ) }}

                            </p>

                        @endif


                        <div class="photographer-card-footer">

                            <a
                                href="{{ route(
                                    'photographers.show',
                                    $photographer->id
                                ) }}"
                                class="photographer-detail-btn"
                            >

                                Lihat Profil

                                <span>
                                    <i class="bi bi-arrow-right"></i>
                                </span>

                            </a>

                        </div>

                    </div>

                </article>


            @empty

                <div class="photographers-empty">

                    <div class="photographers-empty-icon">

                        <i class="bi bi-camera"></i>

                    </div>

                    <h3>
                        Fotografer Tidak Ditemukan
                    </h3>

                    <p>
                        Belum ada fotografer aktif
                        pada cabang yang dipilih.
                    </p>


                    @if (request('branch'))

                        <a href="{{ route('photographers.index') }}">

                            Lihat Semua Fotografer

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
<section class="photographers-cta">

    <div class="container">

        <div class="photographers-cta-box reveal">

            <span>
                Aura Studio
            </span>


            <h2>
                Sudah Menemukan
                Fotografer Pilihanmu?
            </h2>


            <p>
                Selanjutnya pilih layanan dan paket
                yang sesuai dengan kebutuhan sesi Anda.
            </p>


            <div class="photographers-cta-actions">

                <a
                    href="{{ route('services.index') }}"
                    class="photographers-cta-primary"
                >

                    Lihat Layanan

                </a>


                <a
                    href="{{ route('branches.index') }}"
                    class="photographers-cta-secondary"
                >

                    Lihat Cabang

                </a>

            </div>

        </div>

    </div>

</section>


@endsection