@extends('layouts.app')

@section('title', 'Daftar Layanan - Aura Studio')

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

                <div class="service-card-content">

                    <span class="service-card-label">
                        Layanan
                    </span>

                    <h3>
                        {{ $service->name }}
                    </h3>

                    <p>
                        {{ $service->description }}
                    </p>

                    <a href="{{ route('services.show', $service->slug) }}"
                        class="service-card-link">
                        Lihat Detail
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </article>

            @endforeach

        </div>

    </div>

</section>

@endsection