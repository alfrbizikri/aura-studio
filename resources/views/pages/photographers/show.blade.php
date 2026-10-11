@extends('layouts.app')

@section('title', $photographer->name . ' - Fotografer Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/photographers.css') }}">
@endpush

@section('content')

    @php
        $branch = $photographer->branch;
        $first = explode(' ', trim($photographer->name))[0];
        $specs = collect(preg_split('/\s*(?:&|,|\/|\bdan\b)\s*/iu', (string) $photographer->specialization))->map('trim')->filter()->values();
        $specIcons = ['bi-person-heart', 'bi-mortarboard', 'bi-briefcase', 'bi-people', 'bi-sun', 'bi-heart'];

        $services = $branch ? $branch->services : collect();
        $withPhotographer = $services->where('requires_photographer', true);
        $withoutPhotographer = $services->where('requires_photographer', false);
        $hours = ($branch && $branch->opening_time)
            ? substr($branch->opening_time, 0, 5) . ' – ' . substr($branch->closing_time, 0, 5) . ' WIB'
            : null;
        $otherPhotographers = $otherPhotographers ?? collect();
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="ph-dhero has-ring">
        <div class="container">

            <nav class="svc-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span>
                <a href="{{ route('photographers.index') }}">Fotografer</a><span class="sep">/</span>
                <strong>{{ $photographer->name }}</strong>
            </nav>

            <div class="ph-dhero-grid">

                <div class="svc-window">
                    <div class="svc-window-media">
                        {{-- 🖼 GAMBAR: foto profil fotografer → kolom `photo` di tabel photographers --}}
                        @if ($photographer->photo)
                            <img src="{{ asset('storage/' . $photographer->photo) }}" alt="{{ $photographer->name }}">
                        @else
                            <div class="svc-ph"><i class="bi bi-person"></i><span>{{ $photographer->name }}</span></div>
                        @endif
                    </div>
                </div>

                <div class="svc-dhero-copy">
                    <span class="svc-pill">Fotografer Aura Studio</span>
                    <h1>{{ $photographer->name }}</h1>

                    <div class="ph-tags">
                        @if ($photographer->specialization)
                            <span><i class="bi bi-camera"></i>{{ $photographer->specialization }}</span>
                        @endif
                        @if ($branch)
                            <span><i class="bi bi-geo-alt"></i>{{ $branch->name }}</span>
                        @endif
                    </div>

                    @if ($photographer->description)
                        <p class="svc-lead">{{ $photographer->description }}</p>
                    @endif

                    <p class="svc-note">
                        <i class="bi bi-info-circle"></i>
                        <span>Ketersediaan {{ $first }} menyesuaikan layanan, cabang, tanggal, dan sesi yang dipilih saat
                            proses booking.</span>
                    </p>

                    <div class="svc-actions">
                        <a href="{{ route('packages.index') }}" class="btn btn-primary">Mulai Booking <i
                                class="bi bi-arrow-right"></i></a>
                        <a href="{{ route('photographers.index') }}" class="btn btn-outline">Kembali ke Daftar
                            Fotografer</a>
                    </div>
                    <p class="ph-small">Fotografer pilihan akan dicantumkan sebagai preferensi dan dikonfirmasi berdasarkan
                        jadwal.</p>
                </div>

            </div>
        </div>
    </section>


    {{-- ============ CARA KERJA ============ --}}
    <section class="svc-section">
        <div class="container">
            <div class="svc-head">
                <span class="section-eyebrow">Alur sesi</span>
                <h2>Sesi bersama {{ $first }}</h2>
                <p>Dari memilih jadwal sampai hasil foto, begini gambaran sesimu.</p>
            </div>
            <div class="svc-guides">
                <div class="svc-guide"><span class="svc-guide-ico"><i class="bi bi-chat-heart"></i></span>
                    <h3>Arahan yang nyaman</h3>
                    <p>Fotografer mendampingi pose dan suasana selama sesi berlangsung.</p>
                </div>
                <div class="svc-guide"><span class="svc-guide-ico"><i class="bi bi-lightbulb"></i></span>
                    <h3>Sesuai konsep</h3>
                    <p>Sampaikan konsep atau kebutuhanmu saat booking agar sesi terarah.</p>
                </div>
                <div class="svc-guide"><span class="svc-guide-ico"><i class="bi bi-images"></i></span>
                    <h3>Hasil siap dibagikan</h3>
                    <p>Hasil foto mengikuti paket yang kamu pilih.</p>
                </div>
            </div>
        </div>
    </section>


    {{-- ============ SPESIALISASI ============ --}}
    @if ($specs->isNotEmpty())
        <section class="svc-section is-alt">
            <div class="container">
                <div class="svc-head is-left">
                    <span class="section-eyebrow">Kurasi visual</span>
                    <h2>Spesialisasi utama</h2>
                    <p>Gaya fotografi yang menjadi fokus {{ $first }}.</p>
                </div>
                <div class="svc-cards">
                    @foreach ($specs as $spec)
                        <article class="svc-branch">
                            <span class="svc-branch-ico"><i
                                    class="bi {{ $specIcons[$loop->index % count($specIcons)] }}"></i></span>
                            <h3>{{ $spec }}</h3>
                            <p>{{ $first }} berpengalaman menangani sesi {{ strtolower($spec) }}.</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ============ KETENTUAN LAYANAN ============ --}}
    <section class="svc-section">
        <div class="container">
            <div class="ph-terms">
                <div>
                    <span class="section-eyebrow">Ketentuan layanan</span>
                    <h2>Pilih {{ $first }} untuk layanan berikut</h2>
                    <p>Pemilihan fotografer tersedia untuk reservasi sesi yang memerlukan fotografer. Jadwal penugasan
                        mengikuti kalender dan jam operasional cabang terkait.</p>
                </div>
                <div class="ph-terms-cards">
                    @forelse ($withPhotographer as $service)
                        <article class="svc-branch">
                            <span class="svc-branch-ico"><i
                                    class="bi {{ $service->is_on_location ? 'bi-geo-alt' : 'bi-camera' }}"></i></span>
                            <h3>{{ $service->name }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($service->description, 100) }}</p>
                            <div class="svc-branch-actions"><a href="{{ route('services.show', $service->slug) }}"
                                    class="svc-link">Lihat layanan <i class="bi bi-arrow-right"></i></a></div>
                        </article>
                    @empty
                        <p class="svc-empty">Belum ada layanan dengan fotografer di cabang ini.</p>
                    @endforelse
                </div>
            </div>

            @if ($withoutPhotographer->isNotEmpty())
                <p class="svc-note is-center">
                    <i class="bi bi-info-circle"></i>
                    <span><strong>Catatan:</strong> {{ $withoutPhotographer->pluck('name')->implode(', ') }} tidak menggunakan
                        fotografer karena sesi dilakukan secara mandiri.</span>
                </p>
            @endif
        </div>
    </section>


    {{-- ============ CABANG ============ --}}
    @if ($branch)
        <section class="svc-section is-alt">
            <div class="container">
                <article class="ph-branch-card">
                    <div>
                        <span class="section-eyebrow">Cabang fotografer</span>
                        <h2>{{ $branch->name }}</h2>
                        <ul class="brn-facts">
                            <li><i class="bi bi-geo-alt"></i><span>{{ $branch->address }}</span></li>
                            @if ($hours)
                            <li><i class="bi bi-clock"></i><span>{{ $hours }}</span></li>@endif
                        </ul>
                        <p class="ph-small">Cabang pada proses booking akan disesuaikan dengan cabang fotografer.</p>
                        <a href="{{ route('branches.show', $branch->id) }}" class="btn btn-outline">Lihat Detail Cabang</a>
                    </div>
                    <div class="ph-branch-img">
                        <div class="svc-ph tone-3">
                            <i class="bi bi-building"></i>
                            <span>{{ $branch->name }}</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    @endif


    {{-- ============ FOTOGRAFER LAIN ============ --}}
    @if ($otherPhotographers->isNotEmpty())
        <section class="svc-section">
            <div class="container">
                <div class="svc-head is-left">
                    <span class="section-eyebrow">Rekan studio</span>
                    <h2>Fotografer lain di cabang yang sama</h2>
                </div>
                <div class="ph-mini-grid">
                    @foreach ($otherPhotographers as $other)
                        <article class="ph-mini">
                            <div class="ph-mini-photo">
                                {{-- 🖼 GAMBAR: foto fotografer (thumbnail) --}}
                                @if ($other->photo)
                                    <img src="{{ asset('storage/' . $other->photo) }}" alt="{{ $other->name }}" loading="lazy">
                                @else
                                    <div class="svc-ph tone-{{ ($loop->index % 3) + 1 }}"><i class="bi bi-person"></i></div>
                                @endif
                            </div>
                            <div>
                                @if ($other->specialization)<span class="ph-spec">{{ $other->specialization }}</span>@endif
                                <h3>{{ $other->name }}</h3>
                                <a href="{{ route('photographers.show', $other->id) }}" class="svc-link">Lihat profil <i
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
                <span class="section-eyebrow">Reservasi sesi fotografi</span>
                <h2>Siap mewujudkan konsep fotografimu?</h2>
                <p>Pilih layanan, cabang, tanggal, dan sesi yang tersedia untuk melanjutkan reservasi bersama fotografer
                    pilihan.</p>
                <div class="svc-actions is-center">
                    <a href="{{ route('packages.index') }}" class="btn btn-primary">Mulai Booking</a>
                    <a href="{{ route('packages.index') }}" class="btn btn-outline-light">Lihat Paket</a>
                </div>
            </div>
        </div>
    </section>

@endsection