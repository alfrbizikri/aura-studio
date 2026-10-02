@extends('layouts.app')

@section('title', 'Daftar Cabang - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/branches.css') }}">
@endpush

@section('content')

<section class="branches-hero">

    <div class="container">

        <div class="branches-hero-content reveal">

            <div class="section-kicker">
                <span></span>
                Cabang Aura Studio
            </div>

            <h1>
                Temukan Studio
                <br>
                yang Paling Dekat
            </h1>

            <p>
                Pilih cabang Aura Studio yang paling sesuai dengan lokasi
                dan kebutuhan sesi fotografi Anda.
            </p>

        </div>

    </div>

</section>


<section class="branches-section">

    <div class="container">

        <div class="branches-heading-row reveal">

            <div>

                <span class="branches-count">
                    {{ $branches->count() }} Cabang Tersedia
                </span>

                <h2>
                    Pilih Cabang
                </h2>

            </div>

            <p>
                Setiap cabang memiliki layanan, fotografer,
                dan fasilitas yang dapat berbeda.
            </p>

        </div>


        <div class="branches-list">

            @forelse ($branches as $branch)

                <article
                    class="branch-list-card reveal
                    reveal-delay-{{ (($loop->iteration - 1) % 3) + 1 }}"
                >

                    <div class="branch-card-visual">

                        <div class="branch-number">
                            0{{ $loop->iteration }}
                        </div>

                        <div class="branch-icon-large">
                            <i class="bi bi-building"></i>
                        </div>

                        <span class="branch-active-badge">

                            <span></span>

                            Aktif

                        </span>

                    </div>


                    <div class="branch-card-content">

                        <div>

                            <span class="branch-label">
                                Aura Studio
                            </span>

                            <h3>
                                {{ $branch->name }}
                            </h3>

                        </div>


                        <p class="branch-address">

                            <i class="bi bi-geo-alt"></i>

                            {{ $branch->address }}

                        </p>


                        <div class="branch-card-divider"></div>


                        <div class="branch-information">

                            @if ($branch->phone)

                                <div class="branch-info-item">

                                    <i class="bi bi-telephone"></i>

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


                            <div class="branch-info-item">

                                <i class="bi bi-clock"></i>

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


                        @if ($branch->services->isNotEmpty())

                            <div class="branch-services">

                                <span class="branch-services-title">
                                    Layanan tersedia
                                </span>

                                <div class="branch-service-chips">

                                    @foreach ($branch->services->take(3) as $service)

                                        <span>
                                            {{ $service->name }}
                                        </span>

                                    @endforeach

                                </div>

                            </div>

                        @endif


                        <div class="branch-card-actions">

                            <a
                                href="{{ route('branches.show', $branch->id) }}"
                                class="branch-detail-btn"
                            >

                                Lihat Detail

                                <span>
                                    <i class="bi bi-arrow-right"></i>
                                </span>

                            </a>


                            @if ($branch->maps_url)

                                <a
                                    href="{{ $branch->maps_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="branch-map-btn"
                                >

                                    <i class="bi bi-map"></i>

                                    Lihat Peta

                                </a>

                            @endif

                        </div>

                    </div>

                </article>

            @empty

                <div class="branches-empty reveal">

                    <div class="branches-empty-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <h3>
                        Belum Ada Cabang
                    </h3>

                    <p>
                        Data cabang Aura Studio belum tersedia.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection