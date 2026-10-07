@extends('layouts.app')

@section('title', $branch->name . ' - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/packages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/branches.css') }}">
@endpush

@section('content')

    @php
        // GAMBAR CABANG: isi kolom `image` di tabel branches
        // (contoh: images/branches/jakarta.jpg)
        $branchImage = $branch->image ?? null;

        $hours = $branch->opening_time
            ? substr($branch->opening_time, 0, 5)
            . ' – '
            . substr($branch->closing_time, 0, 5)
            . ' WIB'
            : null;

        $wa = preg_replace('/\D+/', '', (string) $branch->phone);

        if (str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }

        $otherBranches = $otherBranches ?? collect();

        $serviceIcon = fn($s) =>
            $s->name === 'Self Photo Studio'
            ? 'bi-person-bounding-box'
            : (
                $s->is_on_location
                ? 'bi-geo-alt'
                : 'bi-camera'
            );

        $serviceMode = fn($s) =>
            $s->is_on_location
            ? 'Di lokasi pilihanmu'
            : (
                $s->requires_photographer
                ? 'Dengan fotografer'
                : 'Foto mandiri'
            );


        /*
        |--------------------------------------------------------------------------
        | HELPER IMAGE
        |--------------------------------------------------------------------------
        | Mendukung gambar dari:
        | - public/images/...
        | - storage/app/public/...
        | - URL eksternal
        */

        $imageUrl = function ($path) {

            if (empty($path)) {
                return null;
            }

            if (
                \Illuminate\Support\Str::startsWith(
                    $path,
                    ['http://', 'https://']
                )
            ) {
                return $path;
            }

            if (
                \Illuminate\Support\Str::startsWith(
                    $path,
                    'storage/'
                )
            ) {
                return asset($path);
            }

            if (
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->exists($path)
            ) {
                return asset('storage/' . $path);
            }

            return asset(ltrim($path, '/'));
        };
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="svc-dhero has-ring">
        <div class="container">
            <div class="svc-hero-grid">

                <div class="svc-dhero-copy">
                    <nav class="svc-crumb" aria-label="Breadcrumb">
                        <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span>
                        <a href="{{ route('branches.index') }}">Cabang</a><span class="sep">/</span>
                        <strong>{{ $branch->name }}</strong>
                    </nav>

                    <span class="svc-pill">Cabang aktif</span>
                    <h1>{{ $branch->name }}</h1>

                    <ul class="brn-facts">
                        <li><i class="bi bi-geo-alt"></i><span>{{ $branch->address }}</span></li>
                        @if ($hours)
                        <li><i class="bi bi-clock"></i><span>{{ $hours }}</span></li>@endif
                        @if ($branch->phone)
                        <li><i class="bi bi-telephone"></i><span>{{ $branch->phone }}</span></li>@endif
                    </ul>

                    <div class="svc-actions">
                        <a href="#layanan" class="btn btn-primary">Lihat Layanan <i class="bi bi-arrow-down"></i></a>
                        @if ($branch->maps_url)
                            <a href="{{ $branch->maps_url }}" target="_blank" rel="noopener" class="btn btn-outline"><i
                                    class="bi bi-map"></i> Lihat di Peta</a>
                        @endif
                    </div>
                    <p class="brn-small">
                        Layanan, paket, sumber daya, lokasi, dan jadwal
                        disesuaikan saat proses booking.
                    </p>
                </div>

                <div class="svc-window">
                    <div class="svc-window-media">
                        {{-- 🖼 GAMBAR: foto utama cabang (tampak depan / interior) --}}
                        @if ($branchImage)
                            <img src="{{ asset($branchImage) }}" alt="{{ $branch->name }}">
                        @else
                            <div class="svc-ph">
                                <i class="bi bi-building"></i>
                                <span>{{ $branch->name }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="svc-caption">
                        <span class="svc-caption-ico"><i class="bi bi-camera"></i></span>
                        <div><strong>Aura Studio</strong><span>{{ $branch->services->count() }} layanan tersedia</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ============ RINGKASAN ============ --}}
    <section class="brn-strip-wrap">
        <div class="container">
            <ul class="brn-strip">
                <li><i class="bi bi-geo-alt"></i>
                    <div><strong>Lokasi</strong><span>{{ \Illuminate\Support\Str::limit($branch->address, 38) }}</span>
                    </div>
                </li>
                @if ($hours)
                    <li><i class="bi bi-clock"></i>
                        <div><strong>{{ substr($branch->opening_time, 0, 5) }} –
                                {{ substr($branch->closing_time, 0, 5) }}</strong><span>Jam operasional</span></div>
                    </li>
                @endif
                @if ($branch->phone)
                    <li><i class="bi bi-telephone"></i>
                        <div><strong>{{ $branch->phone }}</strong><span>Reservasi &amp; konsultasi</span></div>
                    </li>
                @endif
                <li><i class="bi bi-grid"></i>
                    <div><strong>{{ $branch->services->count() }} layanan</strong><span>Tersedia di cabang ini</span></div>
                </li>
            </ul>
        </div>
    </section>


    {{-- ============ LAYANAN ============ --}}
    <section class="svc-section" id="layanan">
        <div class="container">
            <div class="svc-head is-left">
                <span class="section-eyebrow">Kurasi pengalaman</span>
                <h2>Layanan yang tersedia</h2>
                <p>Layanan fotografi yang disediakan cabang {{ $branch->name }}.</p>
            </div>

            <div class="svc-cards">
                @forelse ($branch->services as $service)
                    <article class="svc-branch">
                        <span class="svc-branch-ico"><i class="bi {{ $serviceIcon($service) }}"></i></span>
                        <span class="pkg-tag" style="margin-bottom:14px">{{ $serviceMode($service) }}</span>
                        <h3>{{ $service->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($service->description, 120) }}</p>
                        <div class="svc-branch-actions">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline">Lihat Layanan <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </article>
                @empty
                    <p class="svc-empty">Belum ada layanan di cabang ini.</p>
                @endforelse
            </div>
        </div>
    </section>


    {{-- ============ RUANGAN STUDIO ============ --}}
    @if ($branch->studioRooms->isNotEmpty())
        <section class="svc-section is-alt">
            <div class="container">
                <div class="svc-head is-left">
                    <span class="section-eyebrow">Fasilitas</span>
                    <h2>Ruangan studio</h2>
                    <p>Setiap ruangan dirancang untuk hasil visual terbaik. Jadwal spesifik ditentukan saat reservasi.</p>
                </div>

                <div class="svc-cards">
                    @foreach ($branch->studioRooms as $room)
                        <article class="brn-room">
                            <span class="brn-room-no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $room->name }}</h3>
                            @if ($room->description)
                            <p>{{ $room->description }}</p>@endif
                            <span class="brn-room-status"><i class="bi bi-circle-fill"></i>Aktif</span>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ============ FOTOGRAFER ============ --}}
    @if ($branch->photographers->isNotEmpty())
        <section class="svc-section">
            <div class="container">
                <div class="svc-head is-left">
                    <span class="section-eyebrow">Seniman visual</span>
                    <h2>Fotografer di cabang ini</h2>
                    <p>Setiap fotografer punya gaya khas yang bisa disesuaikan dengan konsep sesimu.</p>
                </div>

                <div class="svc-people">
                    @foreach ($branch->photographers as $person)
                        <article class="svc-person">
                            <div class="svc-person-photo">
                                {{-- 🖼 GAMBAR: foto fotografer → kolom `photo` di tabel photographers --}}
                                @if ($person->photo)
                                    <img src="{{ asset($person->photo) }}" alt="{{ $person->name }}" loading="lazy">
                                @else
                                    <div class="svc-ph tone-{{ ($loop->index % 3) + 1 }}"><i class="bi bi-person"></i></div>
                                @endif
                            </div>
                            <div class="svc-person-body">
                                <h3>{{ $person->name }}</h3>
                                @if ($person->specialization)<span>{{ $person->specialization }}</span>@endif
                                @if ($person->description)<small>{{ \Illuminate\Support\Str::limit($person->description, 90) }}</small>@endif
                                <a href="{{ route('photographers.show', $person->id) }}" class="svc-link"
                                    style="margin-top:14px">Lihat profil <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ============ LOKASI & KONTAK ============ --}}
    <section class="svc-section is-alt">
        <div class="container">
            <div class="svc-head is-left">
                <span class="section-eyebrow">Akses &amp; kontak</span>
                <h2>Lokasi dan kontak</h2>
            </div>

            <div class="brn-loc">

                <div class="brn-map has-ring">
                    <div class="brn-pin"><i class="bi bi-camera-fill"></i></div>
                    <div class="brn-map-label">
                        <strong>{{ $branch->name }}</strong>
                        <span>{{ \Illuminate\Support\Str::limit($branch->address, 60) }}</span>
                    </div>
                    @if ($branch->maps_url)
                        <a href="{{ $branch->maps_url }}" target="_blank" rel="noopener" class="svc-outline"><i
                                class="bi bi-map"></i>Buka di Google Maps</a>
                    @endif
                </div>

                <div class="brn-contact">
                    <div class="brn-contact-row">
                        <span>Alamat lengkap</span>
                        <strong>{{ $branch->address }}</strong>
                    </div>
                    @if ($hours)
                        <div class="brn-contact-row">
                            <span>Jam buka</span>
                            <strong>{{ $hours }}</strong>
                            <small>Jam operasional dapat berbeda pada libur nasional.</small>
                        </div>
                    @endif
                    @if ($branch->phone)
                        <div class="brn-contact-row">
                            <span>Telepon langsung</span>
                            <strong>{{ $branch->phone }}</strong>
                        </div>
                        @if ($wa)
                            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="brn-wa"><i
                                    class="bi bi-whatsapp"></i> Hubungi via WhatsApp</a>
                        @endif
                    @endif
                </div>

            </div>

            <p class="svc-note is-center">
                <i class="bi bi-info-circle"></i>
                <span>
                    <strong>Booking mengikuti ketersediaan layanan dan cabang.</strong>
                    Paket, sumber daya, lokasi, tanggal, dan jadwal
                    yang tersedia akan menyesuaikan layanan yang dipilih.
                </span>
            </p>
        </div>
    </section>


    {{-- ============ CABANG LAIN ============ --}}
    @if ($otherBranches->isNotEmpty())
        <section class="svc-section">
            <div class="container">
                <div class="svc-head is-left">
                    <span class="section-eyebrow">Jangkauan studio</span>
                    <h2>Cabang Aura Studio lainnya</h2>
                </div>

                <div class="svc-cards">
                    @foreach ($otherBranches as $other)
                        <article class="svc-branch">
                            <span class="svc-branch-ico"><i class="bi bi-building"></i></span>
                            <h3>{{ $other->name }}</h3>
                            <p>{{ $other->address }}</p>
                            @if ($other->services->isNotEmpty())
                                <div class="brn-tags" style="margin-bottom:22px">
                                    @foreach ($other->services as $s)<span>{{ $s->name }}</span>@endforeach
                                </div>
                            @endif
                            <div class="svc-branch-actions">
                                <a href="{{ route('branches.show', $other->id) }}" class="btn btn-outline">Lihat Cabang <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ============ CTA ============ --}}
    <section class="svc-cta">
        <div class="container">
            <div class="svc-cta-box has-ring">
                <span class="section-eyebrow">Reservasi</span>
                <h2>Siap mengabadikan momen di {{ $branch->name }}?</h2>
                <p>
                    Pilih paket dan jadwal yang sesuai,
                    lalu amankan slot sesimu dalam beberapa langkah.
                </p>
                <div class="svc-actions is-center">
                    <a href="{{ route('packages.index') }}" class="btn btn-primary">Lihat Paket</a>
                    <a href="{{ route('branches.index') }}" class="btn btn-outline-light">Lihat Semua Cabang</a>
                </div>
            </div>
        </div>
    </section>

@endsection