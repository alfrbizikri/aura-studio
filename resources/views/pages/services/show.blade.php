@extends('layouts.app')

@section('title', $service->name . ' - Aura Studio')

@section('content')

<section class="service-detail-page">

    <div class="container">

        <div class="service-detail-header">

            <a href="{{ route('services.index') }}"
                class="service-back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Layanan
            </a>

            <span class="section-eyebrow">
                Layanan Aura Studio
            </span>

            <h1>
                {{ $service->name }}
            </h1>

            <p>
                {{ $service->description }}
            </p>

        </div>


        <div class="service-detail-info">

            <div class="service-info-item">
                <i class="bi bi-camera"></i>

                <div>
                    <span>Fotografer</span>

                    <strong>
                        {{ $service->requires_photographer ? 'Tersedia' : 'Tidak diperlukan' }}
                    </strong>
                </div>
            </div>


            <div class="service-info-item">
                <i class="bi bi-building"></i>

                <div>
                    <span>Studio</span>

                    <strong>
                        {{ $service->requires_room ? 'Menggunakan studio' : 'Tidak menggunakan studio' }}
                    </strong>
                </div>
            </div>


            <div class="service-info-item">
                <i class="bi bi-geo-alt"></i>

                <div>
                    <span>Lokasi</span>

                    <strong>
                        {{ $service->is_on_location ? 'Lokasi pilihan pelanggan' : 'Aura Studio' }}
                    </strong>
                </div>
            </div>

        </div>


        <div class="service-packages">

            <div class="section-heading">

                <span class="section-eyebrow">
                    Paket Tersedia
                </span>

                <h2>
                    Pilih Paket {{ $service->name }}
                </h2>

            </div>


            <div class="packages-grid">

                @forelse ($service->packages as $package)

                <article class="package-card">

                    <div class="package-card-content">

                        <h3>
                            {{ $package->name }}
                        </h3>

                        <p>
                            {{ $package->description }}
                        </p>

                        <div class="package-card-meta">

                            <span>
                                <i class="bi bi-clock"></i>
                                {{ $package->duration_minutes }} menit
                            </span>

                            @if ($package->max_people)
                            <span>
                                <i class="bi bi-people"></i>
                                Maks. {{ $package->max_people }} orang
                            </span>
                            @endif

                            @if ($package->photo_count)
                            <span>
                                <i class="bi bi-images"></i>
                                {{ $package->photo_count }} foto
                            </span>
                            @endif

                        </div>

                        <div class="package-card-footer">

                            <strong>
                                Rp{{ number_format($package->price, 0, ',', '.') }}
                            </strong>

                            <a href="{{ route('packages.show', $package->id) }}"
                                class="package-card-link">
                                Lihat Paket
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </div>

                </article>

                @empty

                <p>
                    Belum ada paket tersedia untuk layanan ini.
                </p>

                @endforelse

            </div>

        </div>

    </div>

</section>

@endsection