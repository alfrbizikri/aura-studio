@extends('layouts.app')

@section('title', $package->name . ' - Aura Studio')

@section('content')

<section class="package-detail-page">

    <div class="container">

        <div class="package-detail-header">

            <a href="{{ route('packages.index') }}"
               class="service-back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Paket
            </a>

            <span class="section-eyebrow">
                {{ $package->service->name }}
            </span>

            <h1>
                {{ $package->name }}
            </h1>

            <p>
                {{ $package->description }}
            </p>

        </div>


        <div class="package-detail-info">

            <div class="service-info-item">
                <i class="bi bi-clock"></i>

                <div>
                    <span>Durasi</span>
                    <strong>
                        {{ $package->duration_minutes }} menit
                    </strong>
                </div>
            </div>


            @if ($package->max_people)
                <div class="service-info-item">
                    <i class="bi bi-people"></i>

                    <div>
                        <span>Kapasitas</span>
                        <strong>
                            Maks. {{ $package->max_people }} orang
                        </strong>
                    </div>
                </div>
            @endif


            @if ($package->photo_count)
                <div class="service-info-item">
                    <i class="bi bi-images"></i>

                    <div>
                        <span>Jumlah Foto</span>
                        <strong>
                            {{ $package->photo_count }} foto
                        </strong>
                    </div>
                </div>
            @endif

        </div>


        @if ($package->package_includes)
            <div class="package-includes">

                <div class="section-heading">

                    <span class="section-eyebrow">
                        Termasuk Dalam Paket
                    </span>

                    <h2>
                        Fasilitas Paket
                    </h2>

                </div>

                <p>
                    {{ $package->package_includes }}
                </p>

            </div>
        @endif


        <div class="package-detail-price">

            <span>
                Harga Paket
            </span>

            <strong>
                Rp{{ number_format($package->price, 0, ',', '.') }}
            </strong>

            <a href="#" class="btn btn-primary">
                Pilih Paket
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    </div>

</section>

@endsection