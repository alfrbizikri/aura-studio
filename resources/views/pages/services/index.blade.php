@extends('layouts.app')

@section('title', 'Daftar Layanan - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endpush

@section('content')

@php
    $mode = fn ($s) => $s->is_on_location ? 'Di lokasi pilihanmu' : ($s->requires_photographer ? 'Dengan fotografer' : 'Foto mandiri');
    $icon = fn ($s) => $s->name === 'Self Photo Studio' ? 'bi-person-bounding-box' : ($s->is_on_location ? 'bi-geo-alt' : 'bi-camera');
    $minPrice = fn ($s) => $s->packages->min('price');
    $count = $services->count();
@endphp

{{-- ============ HERO ============ --}}
<section class="svc-hero has-ring">
    <div class="container">
        <div class="svc-hero-grid">

            <div class="svc-hero-copy">
                <nav class="svc-crumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Beranda</a>
                    <span class="sep">/</span>
                    <strong>Layanan</strong>
                </nav>

                <span class="svc-pill">Layanan Aura Studio</span>

                <h1>
                    Pilih layanan,
                    <em>abadikan momenmu.</em>
                </h1>

                <p class="svc-lead">
                    Dari foto mandiri di studio sampai sesi bersama fotografer di lokasi
                    pilihanmu. Bandingkan layanannya, lalu pilih yang paling pas.
                </p>

                <ul class="svc-stats">
                    <li>
                        <i class="bi bi-grid"></i>
                        <div><strong>{{ $count }} layanan</strong><span>Siap dipesan</span></div>
                    </li>
                    <li>
                        <i class="bi bi-calendar-check"></i>
                        <div><strong>Booking online</strong><span>Pilih jadwal sendiri</span></div>
                    </li>
                    <li>
                        <i class="bi bi-images"></i>
                        <div><strong>Hasil rapi</strong><span>Siap dibagikan</span></div>
                    </li>
                </ul>
            </div>

            <div class="svc-trio">
                @foreach ($services->take(3) as $service)
                    @php $photo = $service->image ?? null; @endphp
                    <a href="{{ route('services.show', $service->slug) }}"
                       class="svc-arch {{ $photo ? 'has-photo' : '' }}"
                       aria-label="Lihat detail {{ $service->name }}">
                        @if ($photo)
                            <img src="{{ asset($photo) }}" alt="" loading="lazy">
                        @else
                            <i class="bi {{ $icon($service) }} svc-arch-icon"></i>
                        @endif
                        <span class="svc-arch-mode">{{ $mode($service) }}</span>
                        <span class="svc-arch-name">{{ $service->name }}</span>
                        <span class="svc-arch-go"><i class="bi bi-arrow-right"></i></span>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
</section>


{{-- ============ BARIS LAYANAN ============ --}}
<section class="svc-section" id="semua-layanan">
    <div class="container">

        <div class="svc-head">
            <span class="section-eyebrow">Semua Layanan</span>
            <h2>Temukan yang paling cocok</h2>
            <p>Setiap layanan punya suasana dan alur pemesanan sendiri.</p>
        </div>

        <div class="svc-rows">
            @forelse ($services as $service)
                @php
                    $photo = $service->image ?? null;
                    $price = $minPrice($service);
                    $flow = ['Pilih paket', 'Pilih jadwal'];
                    if ($service->requires_photographer) $flow[] = 'Pilih fotografer';
                    $flow[] = 'Bayar';
                @endphp
                <article class="svc-row" id="{{ $service->slug }}">

                    <div class="svc-row-media has-ring">
                        @if ($photo)
                            <img src="{{ asset($photo) }}" alt="{{ $service->name }}" loading="lazy">
                        @else
                            <div class="svc-ph {{ ['', 'tone-2', 'tone-3'][$loop->index % 3] }}">
                                <i class="bi {{ $icon($service) }}"></i>
                                <span>{{ $service->name }}</span>
                            </div>
                        @endif
                        <span class="svc-row-badge">
                            <i class="bi {{ $icon($service) }}"></i>
                            {{ $mode($service) }}
                        </span>
                    </div>

                    <div class="svc-row-body">
                        <span class="svc-pill">{{ $mode($service) }}</span>
                        <h2>{{ $service->name }}</h2>
                        <p>{{ $service->description }}</p>

                        <ul class="svc-checks">
                            <li><i class="bi bi-check-circle-fill"></i>{{ $service->requires_photographer ? 'Didampingi fotografer profesional' : 'Atur sendiri sesi fotomu' }}</li>
                            <li><i class="bi bi-check-circle-fill"></i>{{ $service->is_on_location ? 'Lokasi sesuai pilihanmu' : 'Sesi di studio Aura' }}</li>
                            <li><i class="bi bi-check-circle-fill"></i>Pilih jadwal lewat booking online</li>
                        </ul>

                        <div class="svc-flow">
                            <span class="svc-flow-title">Alur pemesanan</span>
                            <ol>
                                @foreach ($flow as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>

                        <div class="svc-row-foot">
                            @if ($price)
                                <div class="svc-price-from">
                                    <span>Paket mulai dari</span>
                                    <strong>Rp{{ number_format($price, 0, ',', '.') }}<small>/ sesi</small></strong>
                                </div>
                            @endif
                            <div class="svc-actions">
                                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-primary">
                                    Lihat Detail Layanan <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="{{ route('services.show', $service->slug) }}#paket" class="svc-link">
                                    Lihat paket <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </article>
            @empty
                <p class="svc-empty">Belum ada layanan tersedia.</p>
            @endforelse
        </div>

    </div>
</section>


{{-- ============ TABEL PERBANDINGAN ============ --}}
@if ($count > 1)
<section class="svc-section is-alt">
    <div class="container">

        <div class="svc-head">
            <span class="section-eyebrow">Bandingkan</span>
            <h2>Perbedaan tiap layanan</h2>
            <p>Lihat sekilas apa yang kamu dapatkan di setiap layanan.</p>
        </div>

        <div class="svc-compare">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Yang dibandingkan</th>
                        @foreach ($services as $service)
                            <th scope="col">
                                <span class="svc-th-ico"><i class="bi {{ $icon($service) }}"></i></span>
                                <small>{{ $mode($service) }}</small>
                                <strong>{{ $service->name }}</strong>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Fotografer</th>
                        @foreach ($services as $service)
                            <td>
                                @if ($service->requires_photographer)
                                    <span class="svc-yes"><i class="bi bi-check-lg"></i>Tersedia</span>
                                @else
                                    <span class="svc-no">Tidak diperlukan</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Studio</th>
                        @foreach ($services as $service)
                            <td>
                                @if ($service->requires_room)
                                    <span class="svc-yes"><i class="bi bi-check-lg"></i>Menggunakan studio</span>
                                @else
                                    <span class="svc-no">Tidak menggunakan studio</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Lokasi</th>
                        @foreach ($services as $service)
                            <td>{{ $service->is_on_location ? 'Lokasi pilihanmu' : 'Aura Studio' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Paket mulai dari</th>
                        @foreach ($services as $service)
                            <td class="svc-money">
                                {{ $minPrice($service) ? 'Rp' . number_format($minPrice($service), 0, ',', '.') : '-' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row"><span class="visually-hidden">Detail</span></th>
                        @foreach ($services as $service)
                            <td><a href="{{ route('services.show', $service->slug) }}" class="svc-link">Lihat detail <i class="bi bi-arrow-right"></i></a></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="svc-scroll-hint"><i class="bi bi-arrow-left-right"></i> Geser tabel untuk melihat semua layanan</p>

    </div>
</section>
@endif


{{-- ============ PANDUAN MEMILIH ============ --}}
<section class="svc-section">
    <div class="container">

        <div class="svc-head">
            <span class="section-eyebrow">Masih bingung?</span>
            <h2>Pilih berdasarkan kebutuhanmu</h2>
        </div>

        @php
            $pick = fn ($test) => $services->first($test);
            $guides = [
                ['bi-person-bounding-box', 'Ingin santai & bebas bereksplorasi', 'Foto mandiri', 'Cocok kalau kamu ingin mengatur gaya dan pose sendiri.', $pick(fn ($s) => !$s->requires_photographer)],
                ['bi-camera', 'Ingin hasil terarah profesional', 'Dengan fotografer', 'Fotografer membantu pose, angle, dan suasana sesi.', $pick(fn ($s) => $s->requires_photographer && !$s->is_on_location)],
                ['bi-geo-alt', 'Ingin suasana di tempat favorit', 'Sesi di lokasi', 'Pilih lokasi yang bermakna untuk momenmu.', $pick(fn ($s) => $s->is_on_location)],
            ];
        @endphp

        <div class="svc-guides">
            @foreach ($guides as [$gIcon, $gSmall, $gTitle, $gText, $gService])
                <div class="svc-guide">
                    <span class="svc-guide-ico"><i class="bi {{ $gIcon }}"></i></span>
                    <small>{{ $gSmall }}</small>
                    <h3>{{ $gTitle }}</h3>
                    @if ($gService)
                        <span class="svc-guide-reco">{{ $gService->name }}</span>
                    @endif
                    <p>{{ $gText }}</p>
                    @if ($gService)
                        <a href="{{ route('services.show', $gService->slug) }}" class="svc-link">
                            Lihat layanan <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <p class="svc-note is-center">
            <i class="bi bi-info-circle"></i>
            <span><strong>Catatan:</strong> ketersediaan jadwal, studio, dan fotografer ditampilkan saat kamu melakukan booking.</span>
        </p>

    </div>
</section>


{{-- ============ CTA ============ --}}
<section class="svc-cta">
    <div class="container">
        <div class="svc-cta-box has-ring">
            <span class="section-eyebrow">Siap berfoto?</span>
            <h2>Pilih layanannya, amankan jadwalmu.</h2>
            <p>Booking online hanya butuh beberapa langkah.</p>
            <div class="svc-actions is-center">
                <a href="#semua-layanan" class="btn btn-primary">Mulai pilih layanan <i class="bi bi-arrow-down"></i></a>
                <a href="{{ url('/') }}" class="btn btn-outline-light">Kembali ke beranda</a>
            </div>
        </div>
    </div>
</section>

@endsection
