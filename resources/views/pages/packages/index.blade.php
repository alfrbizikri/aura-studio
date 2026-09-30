@extends('layouts.app')

@section('title', 'Daftar Paket - Aura Studio')

@section('content')

<section class="packages-page">

    <div class="container">

        <div class="section-heading">

            <span class="section-eyebrow">
                Paket Aura Studio
            </span>

            <h1>
                Pilih Paket Fotografi yang Sesuai
            </h1>

            <p>
                Temukan paket fotografi berdasarkan layanan,
                durasi, kapasitas, dan kebutuhanmu.
            </p>

        </div>

        <div class="packages-grid">

            @foreach ($packages as $package)

            <article class="package-card">

                <div class="package-card-content">

                    <span class="package-card-label">
                        {{ $package->service->name }}
                    </span>

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
                            Lihat Detail
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </article>

            @endforeach

        </div>

    </div>

</section>

@endsection