@extends('layouts.app')

@section('title', 'Riwayat Booking - Aura Studio')

@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/customer-bookings.css') }}">
@endpush

@section('content')

<section class="booking-history">
    <div class="container">

        <div class="booking-history-header">
            <span class="booking-kicker">Akun Saya</span>

            <h1>Riwayat Booking</h1>

            <p>
                Lihat seluruh pemesanan yang pernah Anda lakukan
                di Aura Studio.
            </p>
        </div>


        @if ($bookings->isEmpty())

        <div class="booking-empty">
            <i class="bi bi-calendar-x"></i>

            <h2>Belum Ada Booking</h2>

            <p>
                Anda belum memiliki riwayat booking.
                Silakan pilih layanan atau paket yang tersedia.
            </p>

            <a href="{{ route('packages.index') }}"
                class="btn btn-primary">
                Lihat Paket
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @else

        <div class="booking-list">

            @foreach ($bookings as $booking)

            <article class="booking-card">

                <div class="booking-card-top">

                    <div>
                        <span class="booking-label">
                            Kode Booking
                        </span>

                        <h2>
                            {{ $booking->booking_code }}
                        </h2>
                    </div>


                    <span class="
                                booking-status
                                booking-status-{{ $booking->status }}
                            ">
                        {{ ucfirst($booking->status) }}
                    </span>

                </div>


                <div class="booking-card-body">

                    <div class="booking-info">

                        <span class="booking-label">
                            Paket
                        </span>

                        <strong>
                            {{ $booking->package->name ?? 'Paket tidak tersedia' }}
                        </strong>

                    </div>


                    <div class="booking-info">

                        <span class="booking-label">
                            Jumlah Orang
                        </span>

                        <strong>
                            {{ $booking->number_of_people }} orang
                        </strong>

                    </div>


                    <div class="booking-info">

                        <span class="booking-label">
                            Total
                        </span>

                        <strong>
                            Rp {{ number_format(
                                        $booking->total_price,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                        </strong>

                    </div>


                    <div class="booking-info">

                        <span class="booking-label">
                            Tanggal Pemesanan
                        </span>

                        <strong>
                            {{ $booking->created_at->format('d M Y') }}
                        </strong>

                    </div>

                </div>


                <div class="booking-card-footer">

                    <span>
                        <i class="bi bi-clock"></i>

                        Dibuat
                        {{ $booking->created_at->diffForHumans() }}
                    </span>

                    <a href="{{ route('customer.bookings.show', $booking) }}"
                        class="booking-detail-link">
                        Detail Booking
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </article>

            @endforeach

        </div>

        @endif

    </div>
</section>

@endsection