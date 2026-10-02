@extends('layouts.app')

@section('title', 'Daftar Layanan - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endpush

@section('content')

<section class="services-page">

    <div class="container">

        <div class="section-heading">

            <span class="section-eyebrow">
                Layanan Aura Studio
            </span>

            <h1>
                Pilih Layanan Fotografi Sesuai Kebutuhanmu
            </h1>

            <p>
                Temukan layanan fotografi yang sesuai untuk momen,
                kebutuhan, dan pengalaman yang kamu inginkan.
            </p>

        </div>

        <div class="services-grid">

            @foreach ($services as $service)

            <article class="service-card">

                <div class="service-card-top">

                    <span class="service-tag">
                        {{ $service->is_on_location ? 'Di lokasi pilihanmu' : ($service->requires_photographer ? 'Dengan fotografer' : 'Foto mandiri') }}
                    </span>

                    <span class="service-card-icon">

                        @if ($service->name === 'Self Photo Studio')
                            <i class="bi bi-person-bounding-box"></i>

                        @elseif ($service->is_on_location)
                            <i class="bi bi-geo-alt"></i>

                        @else
                            <i class="bi bi-camera"></i>

                        @endif

                    </span>

                </div>

                <h3>
                    {{ $service->name }}
                </h3>

                <p>
                    {{ $service->description }}
                </p>

                <a href="{{ route('services.show', $service->slug) }}"
                    class="service-card-button">
                    Lihat Detail
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

            @endforeach

        </div>

    </div>

</section>

@endsection