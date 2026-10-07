@extends('layouts.app')

@section('title', $service->name . ' - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endpush

@section('content')

@php
    $mode = $service->is_on_location ? 'Di lokasi pilihanmu' : ($service->requires_photographer ? 'Dengan fotografer' : 'Foto mandiri');
    $icon = $service->name === 'Self Photo Studio' ? 'bi-person-bounding-box' : ($service->is_on_location ? 'bi-geo-alt' : 'bi-camera');
    $photo = $service->image ?? null;
    $packages = $service->packages;
    $minPrice = $packages->min('price');
    $firstPackage = $packages->first();
    $branches = $branches ?? collect();
    $photographers = $photographers ?? collect();
    $galleries = $galleries ?? collect();
    $otherServices = $otherServices ?? collect();

    $steps = [['bi-box-seam', 'Pilih paket', 'Tentukan paket sesuai kebutuhan sesimu.']];
    $steps[] = ['bi-calendar-event', 'Pilih jadwal', 'Pilih tanggal dan jam yang tersedia.'];
    if ($service->requires_photographer) $steps[] = ['bi-person-video2', 'Pilih fotografer', 'Pilih fotografer yang cocok dengan gayamu.'];
    $steps[] = ['bi-credit-card', 'Bayar & konfirmasi', 'Selesaikan pembayaran, lalu terima konfirmasi booking.'];

    $faqs = [
        ['Bagaimana cara memesan ' . $service->name . '?', 'Pilih paket, tentukan jadwal' . ($service->requires_photographer ? ', pilih fotografer' : '') . ', lalu selesaikan pembayaran. Konfirmasi booking akan kamu terima setelahnya.'],
        ['Di mana sesi dilakukan?', $service->is_on_location ? 'Sesi dilakukan di lokasi pilihanmu, sesuai kesepakatan saat booking.' : 'Sesi dilakukan di Aura Studio' . ($service->requires_room ? ' menggunakan ruang studio yang kamu pesan.' : '.')],
        ['Apakah fotografer disediakan?', $service->requires_photographer ? 'Ya, kamu akan didampingi fotografer profesional selama sesi.' : 'Layanan ini tidak memerlukan fotografer, kamu mengatur sesi sendiri.'],
        ['Bisakah menjadwalkan ulang?', 'Hubungi kami sebelum jadwal dimulai agar kami bisa membantu mengatur ulang sesimu.'],
    ];
@endphp

{{-- ============ HERO ============ --}}
<section class="svc-dhero has-ring">
    <div class="container">
        <div class="svc-hero-grid">

            <div class="svc-dhero-copy">
                <nav class="svc-crumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Beranda</a>
                    <span class="sep">/</span>
                    <a href="{{ route('services.index') }}">Layanan</a>
                    <span class="sep">/</span>
                    <strong>{{ $service->name }}</strong>
                </nav>

                <span class="svc-pill">{{ $mode }}</span>

                <h1>{{ $service->name }}</h1>

                <p class="svc-lead">{{ $service->description }}</p>

                @if ($minPrice)
                    <div class="svc-from">
                        <span>Paket mulai dari</span>
                        <strong>Rp{{ number_format($minPrice, 0, ',', '.') }}<small>/ sesi</small></strong>
                        <em>{{ $packages->count() }} paket tersedia</em>
                    </div>
                @endif

                <div class="svc-actions">
                    <a href="#paket" class="btn btn-primary">Lihat Paket <i class="bi bi-arrow-down"></i></a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline">Semua Layanan</a>
                </div>

                <ul class="svc-feats">
                    <li><i class="bi bi-camera"></i>{{ $service->requires_photographer ? 'Dengan fotografer' : 'Tanpa fotografer' }}</li>
                    <li><i class="bi bi-building"></i>{{ $service->requires_room ? 'Di studio' : 'Tanpa studio' }}</li>
                    <li><i class="bi bi-geo-alt"></i>{{ $service->is_on_location ? 'Di lokasi' : 'Aura Studio' }}</li>
                </ul>
            </div>

            <div class="svc-window">
                <div class="svc-window-media">
                    @if ($photo)
                        <img src="{{ asset($photo) }}" alt="{{ $service->name }}">
                    @else
                        <div class="svc-ph">
                            <i class="bi {{ $icon }}"></i>
                            <span>{{ $service->name }}</span>
                        </div>
                    @endif
                </div>
                <div class="svc-caption">
                    <span class="svc-caption-ico"><i class="bi {{ $icon }}"></i></span>
                    <div>
                        <strong>{{ $service->name }}</strong>
                        <span>{{ $mode }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============ FASILITAS ============ --}}
<section class="svc-section">
    <div class="container">
        <div class="svc-fac">

            <div class="svc-spec has-ring">
                <h3>Ringkasan layanan</h3>
                <ul class="svc-spec-list">
                    <li>
                        <span class="svc-spec-ico"><i class="bi bi-camera"></i></span>
                        <div><span>Fotografer</span><strong>{{ $service->requires_photographer ? 'Tersedia' : 'Tidak diperlukan' }}</strong></div>
                    </li>
                    <li>
                        <span class="svc-spec-ico"><i class="bi bi-building"></i></span>
                        <div><span>Studio</span><strong>{{ $service->requires_room ? 'Menggunakan studio' : 'Tidak menggunakan studio' }}</strong></div>
                    </li>
                    <li>
                        <span class="svc-spec-ico"><i class="bi bi-geo-alt"></i></span>
                        <div><span>Lokasi</span><strong>{{ $service->is_on_location ? 'Lokasi pilihan pelanggan' : 'Aura Studio' }}</strong></div>
                    </li>
                </ul>
            </div>

            <div class="svc-fac-copy">
                <span class="section-eyebrow">Yang kamu dapatkan</span>
                <h2>Sesi yang nyaman dari awal sampai akhir</h2>
                <p>{{ $service->description }}</p>

                <ul class="svc-checks is-cols">
                    <li><i class="bi bi-check-circle-fill"></i>Booking online</li>
                    <li><i class="bi bi-check-circle-fill"></i>Jadwal sesuai pilihanmu</li>
                    <li><i class="bi bi-check-circle-fill"></i>{{ $service->requires_photographer ? 'Pendampingan fotografer' : 'Bebas atur gaya sendiri' }}</li>
                    <li><i class="bi bi-check-circle-fill"></i>{{ $service->is_on_location ? 'Lokasi fleksibel' : 'Fasilitas studio lengkap' }}</li>
                </ul>

                <p class="svc-note">
                    <i class="bi bi-info-circle"></i>
                    <span><strong>Catatan:</strong> detail durasi, jumlah orang, dan jumlah foto berbeda di setiap paket.</span>
                </p>
            </div>

        </div>
    </div>
</section>


{{-- ============ PAKET ============ --}}
<section class="svc-section is-alt" id="paket">
    <div class="container">

        <div class="svc-head">
            <span class="section-eyebrow">Paket Tersedia</span>
            <h2>Pilih paket {{ $service->name }}</h2>
            <p>Semua paket bisa dipesan online dan dijadwalkan sesuai waktumu.</p>
        </div>

        <div class="svc-plans">
            @forelse ($packages as $package)
                @php $featured = $packages->count() === 3 && $loop->iteration === 2; @endphp
                <article class="svc-plan {{ $featured ? 'is-featured' : '' }}">
                    @if ($featured)
                        <span class="svc-plan-ribbon">Paling populer</span>
                    @endif

                    <h3>{{ $package->name }}</h3>
                    <div class="svc-plan-price">Rp{{ number_format($package->price, 0, ',', '.') }}<small>/ sesi</small></div>

                    <div class="svc-chips">
                        <span><i class="bi bi-clock"></i>{{ $package->duration_minutes }} menit</span>
                        @if ($package->max_people)
                            <span><i class="bi bi-people"></i>Maks. {{ $package->max_people }} orang</span>
                        @endif
                        @if ($package->photo_count)
                            <span><i class="bi bi-images"></i>{{ $package->photo_count }} foto</span>
                        @endif
                    </div>

                    <div class="svc-plan-body">
                        <p>{{ $package->description }}</p>
                    </div>

                    <a href="{{ route('packages.show', $package->id) }}" class="btn btn-primary">
                        Lihat Paket <i class="bi bi-arrow-right"></i>
                    </a>
                </article>
            @empty
                <p class="svc-empty">Belum ada paket tersedia untuk layanan ini.</p>
            @endforelse
        </div>

    </div>
</section>


{{-- ============ ALUR BOOKING ============ --}}
<section class="svc-section">
    <div class="container">

        <div class="svc-head">
            <span class="section-eyebrow">Cara Booking</span>
            <h2>{{ count($steps) }} langkah menuju sesimu</h2>
        </div>

        <ol class="svc-steps" style="--n: {{ count($steps) }}">
            @foreach ($steps as [$sIcon, $sTitle, $sText])
                <li class="svc-step">
                    <div class="svc-step-head"><i class="bi {{ $sIcon }}"></i></div>
                    <h3>{{ $sTitle }}</h3>
                    <p>{{ $sText }}</p>
                </li>
            @endforeach
        </ol>

    </div>
</section>


{{-- ============ CABANG (opsional) ============ --}}
@if ($branches->isNotEmpty())
<section class="svc-section is-alt">
    <div class="container">
        <div class="svc-head">
            <span class="section-eyebrow">Cabang</span>
            <h2>Tersedia di cabang kami</h2>
        </div>
        <div class="svc-cards">
            @foreach ($branches as $branch)
                <article class="svc-branch">
                    <span class="svc-branch-ico"><i class="bi bi-geo-alt"></i></span>
                    <h3>{{ $branch->name }}</h3>
                    @if (!empty($branch->address))
                        <p>{{ $branch->address }}</p>
                    @endif
                    <div class="svc-meta">
                        @if (!empty($branch->phone))
                            <span><i class="bi bi-telephone"></i>{{ $branch->phone }}</span>
                        @endif
                        @if (!empty($branch->opening_time))
                            <span><i class="bi bi-clock"></i>{{ substr($branch->opening_time, 0, 5) }} – {{ substr($branch->closing_time, 0, 5) }} WIB</span>
                        @endif
                    </div>
                    <div class="svc-branch-actions">
                        <a href="{{ route('branches.show', $branch->id) }}" class="svc-link">Lihat cabang <i class="bi bi-arrow-right"></i></a>
                        @if (!empty($branch->maps_url))
                            <a href="{{ $branch->maps_url }}" target="_blank" rel="noopener" class="svc-outline"><i class="bi bi-map"></i>Peta</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ============ FOTOGRAFER (opsional) ============ --}}
@if ($service->requires_photographer && $photographers->isNotEmpty())
<section class="svc-section">
    <div class="container">
        <div class="svc-head">
            <span class="section-eyebrow">Fotografer</span>
            <h2>Siapa yang mendampingimu</h2>
        </div>
        <div class="svc-people">
            @foreach ($photographers as $person)
                <article class="svc-person">
                    <div class="svc-person-photo">
                        @if (!empty($person->photo))
                            <img src="{{ asset($person->photo) }}" alt="{{ $person->name }}" loading="lazy">
                        @else
                            <div class="svc-ph tone-{{ ($loop->index % 3) + 1 }}"><i class="bi bi-person"></i></div>
                        @endif
                    </div>
                    <div class="svc-person-body">
                        <h3>{{ $person->name }}</h3>
                        @if (!empty($person->specialization))
                            <span>{{ $person->specialization }}</span>
                        @endif
                        @if (!empty($person->description))
                            <small>{{ \Illuminate\Support\Str::limit($person->description, 70) }}</small>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ============ GALERI (opsional) ============ --}}
@if ($galleries->isNotEmpty())
<section class="svc-section svc-band">
    <div class="container">
        <div class="svc-head">
            <span class="section-eyebrow">Galeri</span>
            <h2>Hasil dari {{ $service->name }}</h2>
        </div>
        <div class="svc-gallery">
            @foreach ($galleries as $shot)
                <figure class="svc-shot">
                    <img src="{{ asset($shot->image) }}" alt="{{ $shot->title ?? $service->name }}" loading="lazy">
                    @if (!empty($shot->title))
                        <figcaption>{{ $shot->title }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ============ FAQ ============ --}}
<section class="svc-section {{ $branches->isNotEmpty() || $galleries->isNotEmpty() ? '' : 'is-alt' }}">
    <div class="container">
        <div class="svc-head">
            <span class="section-eyebrow">FAQ</span>
            <h2>Pertanyaan yang sering muncul</h2>
        </div>
        <div class="svc-faq">
            @foreach ($faqs as [$q, $a])
                <details {{ $loop->first ? 'open' : '' }}>
                    <summary>{{ $q }}<i class="bi bi-chevron-down"></i></summary>
                    <div class="svc-faq-body">{{ $a }}</div>
                </details>
            @endforeach
        </div>
    </div>
</section>


{{-- ============ LAYANAN LAIN ============ --}}
@if ($otherServices->isNotEmpty())
<section class="svc-section" style="padding-top:0">
    <div class="container">
        <div class="svc-head">
            <h2>Layanan lainnya</h2>
        </div>
        <div class="svc-others">
            @foreach ($otherServices as $other)
                <a href="{{ route('services.show', $other->slug) }}">
                    <i class="bi bi-camera"></i>{{ $other->name }}<i class="bi bi-arrow-right"></i>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ============ CTA ============ --}}
<section class="svc-cta">
    <div class="container">
        <div class="svc-cta-box has-ring">
            <span class="section-eyebrow">Siap berfoto?</span>
            <h2>Mulai dari memilih paket {{ $service->name }}.</h2>
            <p>Booking online hanya butuh beberapa langkah.</p>
            <div class="svc-actions is-center">
                <a href="#paket" class="btn btn-primary">Pilih paket <i class="bi bi-arrow-up"></i></a>
                <a href="{{ route('services.index') }}" class="btn btn-outline-light">Lihat layanan lain</a>
            </div>
        </div>
    </div>
</section>

@endsection
