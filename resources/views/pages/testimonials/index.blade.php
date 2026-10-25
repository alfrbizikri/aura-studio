@extends('layouts.app')

@section('title', 'Ulasan Pelanggan - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/testimonials.css') }}">
@endpush

@section('content')

    {{-- HERO --}}
    <section class="reviews-hero">
        <div class="container reviews-hero-layout">

            <div class="reviews-hero-copy">
                <span class="reviews-eyebrow">
                    <i class="bi bi-stars"></i>
                    CERITA PELANGGAN
                </span>

                <h1>
                    Pengalaman Nyata,
                    <span>Momen Berharga.</span>
                </h1>

                <p>
                    Dengarkan pengalaman pelanggan yang telah
                    mengabadikan momen bersama Aura Studio.
                    Setiap ulasan membantu kami terus berkembang.
                </p>
            </div>

            <aside class="reviews-summary">
                <span class="reviews-summary-label">
                    Rating Pelanggan
                </span>

                <strong class="reviews-average">
                    @if ($publishedCount > 0)
                        {{ number_format((float) $averageRating, 1, ',', '.') }}
                    @else
                        —
                    @endif
                    <small>/ 5</small>
                </strong>

                <div class="reviews-summary-stars"
                    aria-label="Rating rata-rata pelanggan">

                    @for ($star = 1; $star <= 5; $star++)
                        <i class="bi bi-star{{ $publishedCount > 0 && $star <= round($averageRating) ? '-fill' : '' }}"></i>
                    @endfor

                </div>

                <p>
                    {{ number_format($publishedCount, 0, ',', '.') }}
                    ulasan dipublikasikan
                </p>
            </aside>

        </div>
    </section>

    {{-- DAFTAR ULASAN --}}
    <section class="reviews-main">
        <div class="container">

            <div class="reviews-list-heading">
                <div>
                    <span class="reviews-eyebrow">SUARA PELANGGAN</span>
                    <h2>Ulasan dan Rating</h2>
                    <p>
                        Pengalaman pelanggan setelah menggunakan
                        layanan Aura Studio.
                    </p>
                </div>

                <div class="reviews-count">
                    {{ $publishedCount }}
                    {{ $publishedCount === 1 ? 'ulasan' : 'ulasan' }}
                </div>
            </div>

            @if ($testimonials->isNotEmpty())

                <div class="reviews-grid">

                    @foreach ($testimonials as $testimonial)

                        <article class="public-review-card">

                            <div class="public-review-top">
                                <div class="public-review-stars"
                                    aria-label="Rating {{ $testimonial->rating }} dari 5">

                                    @for ($star = 1; $star <= 5; $star++)
                                        <i class="bi bi-star{{ $star <= $testimonial->rating ? '-fill' : '' }}"></i>
                                    @endfor

                                    <strong>
                                        {{ $testimonial->rating }}.0
                                    </strong>
                                </div>

                                <time datetime="{{ $testimonial->created_at?->toDateString() }}">
                                    {{ $testimonial->created_at?->format('d/m/Y') ?? '-' }}
                                </time>
                            </div>

                            <div class="public-review-quote" aria-hidden="true">
                                “
                            </div>

                            <p class="public-review-comment">
                                {{ $testimonial->comment }}
                            </p>

                            <div class="public-review-footer">

                                <div class="public-review-avatar" aria-hidden="true">
                                    {{ mb_strtoupper(mb_substr($testimonial->booking?->user?->name ?? 'P', 0, 1)) }}
                                </div>

                                <div class="public-review-customer">
                                    <strong>
                                        {{ $testimonial->booking?->user?->name ?? 'Pelanggan Aura Studio' }}
                                    </strong>

                                    <span>
                                        {{ $testimonial->booking?->package?->name ?? 'Pelanggan Aura Studio' }}
                                    </span>
                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

                {{-- PAGINATION --}}
                @if ($testimonials->hasPages())
                    <nav class="reviews-pagination"
                        aria-label="Navigasi halaman ulasan">

                        @if ($testimonials->previousPageUrl())
                            <a href="{{ $testimonials->previousPageUrl() }}"
                                class="reviews-page-link">
                                <i class="bi bi-arrow-left"></i>
                                Sebelumnya
                            </a>
                        @else
                            <span class="reviews-page-link is-disabled">
                                <i class="bi bi-arrow-left"></i>
                                Sebelumnya
                            </span>
                        @endif

                        <span class="reviews-page-number">
                            Halaman {{ $testimonials->currentPage() }}
                            dari {{ $testimonials->lastPage() }}
                        </span>

                        @if ($testimonials->nextPageUrl())
                            <a href="{{ $testimonials->nextPageUrl() }}"
                                class="reviews-page-link">
                                Berikutnya
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <span class="reviews-page-link is-disabled">
                                Berikutnya
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        @endif

                    </nav>
                @endif

            @else

                <div class="reviews-empty">
                    <div class="reviews-empty-icon">
                        <i class="bi bi-chat-square-heart"></i>
                    </div>

                    <h3>Ulasan pelanggan akan hadir di sini</h3>

                    <p>
                        Ulasan yang telah disetujui admin akan tampil
                        di halaman ini. Terima kasih telah mengenal
                        Aura Studio.
                    </p>

                    <a href="{{ route('services.index') }}"
                        class="reviews-primary-link">
                        Jelajahi Layanan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            @endif

        </div>
    </section>

@endsection