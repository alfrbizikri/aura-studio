@extends('layouts.app')

@section('title', 'Detail Booking - Aura Studio')

@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/customer-bookings.css') }}">
@endpush

@section('content')

<section class="booking-detail-page">

    <div class="container">

        <div class="booking-detail-heading">

            <a href="{{ route('customer.bookings.index') }}"
                class="booking-back">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Riwayat
            </a>

            <span class="booking-kicker">
                Detail Pemesanan
            </span>

            <div class="booking-detail-title">

                <div>
                    <h1>
                        {{ $booking->booking_code }}
                    </h1>

                    <p>
                        Dibuat pada
                        {{ $booking->created_at->format('d M Y, H:i') }}
                    </p>
                </div>

                <span class="
                    booking-status
                    booking-status-{{ $booking->status }}
                ">
                    {{ ucfirst($booking->status) }}
                </span>

            </div>

        </div>


        <div class="booking-detail-grid">

            {{-- INFORMASI BOOKING --}}
            <div class="booking-detail-main">

                <div class="booking-detail-card">

                    <div class="detail-card-header">
                        <div>
                            <span class="booking-label">
                                Pemesanan
                            </span>

                            <h2>Informasi Booking</h2>
                        </div>

                        <i class="bi bi-calendar-check"></i>
                    </div>


                    <div class="detail-info-grid">

                        <div class="detail-info-item">
                            <span>Paket</span>

                            <strong>
                                {{ $booking->package->name ?? '-' }}
                            </strong>
                        </div>


                        <div class="detail-info-item">
                            <span>Layanan</span>

                            <strong>
                                {{ $booking->package?->service?->name ?? '-' }}
                            </strong>
                        </div>


                        <div class="detail-info-item">
                            <span>Jumlah Orang</span>

                            <strong>
                                {{ $booking->number_of_people }} orang
                            </strong>
                        </div>


                        <div class="detail-info-item">
                            <span>Total Harga</span>

                            <strong>
                                Rp {{ number_format(
                                    $booking->total_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>
                        </div>

                    </div>


                    @if ($booking->location_address)

                    <div class="detail-note">

                        <span>Alamat Lokasi</span>

                        <p>
                            {{ $booking->location_address }}
                        </p>

                    </div>

                    @endif


                    @if ($booking->location_notes)

                    <div class="detail-note">

                        <span>Catatan Lokasi</span>

                        <p>
                            {{ $booking->location_notes }}
                        </p>

                    </div>

                    @endif


                    @if ($booking->notes)

                    <div class="detail-note">

                        <span>Catatan Booking</span>

                        <p>
                            {{ $booking->notes }}
                        </p>

                    </div>

                    @endif

                </div>


                {{-- JADWAL --}}
                <div class="booking-detail-card">

                    <div class="detail-card-header">

                        <div>
                            <span class="booking-label">
                                Sesi Foto
                            </span>

                            <h2>Jadwal Booking</h2>
                        </div>

                        <i class="bi bi-clock-history"></i>

                    </div>


                    @forelse ($booking->schedules as $schedule)

                    <div class="schedule-detail">

                        <div class="detail-info-grid">

                            <div class="detail-info-item">

                                <span>Tanggal</span>

                                <strong>
                                    {{ $schedule->schedule_date->format('d M Y') }}
                                </strong>

                            </div>


                            <div class="detail-info-item">

                                <span>Waktu</span>

                                <strong>
                                    {{ substr($schedule->start_time, 0, 5) }}
                                    -
                                    {{ substr($schedule->end_time, 0, 5) }}
                                </strong>

                            </div>


                            <div class="detail-info-item">

                                <span>Cabang</span>

                                <strong>
                                    {{ $schedule->branch->name ?? '-' }}
                                </strong>

                            </div>


                            <div class="detail-info-item">

                                <span>Status Jadwal</span>

                                <strong>
                                    {{ ucfirst($schedule->status) }}
                                </strong>

                            </div>


                            <div class="detail-info-item">

                                <span>Fotografer</span>

                                <strong>
                                    {{ $schedule->photographer->name ?? 'Belum ditentukan' }}
                                </strong>

                            </div>


                            <div class="detail-info-item">

                                <span>Studio Room</span>

                                <strong>
                                    {{ $schedule->studioRoom->name ?? 'Tidak menggunakan studio room' }}
                                </strong>

                            </div>

                        </div>

                    </div>

                    @empty

                    <div class="detail-empty">

                        <i class="bi bi-calendar2-x"></i>

                        <div>
                            <strong>
                                Jadwal belum tersedia
                            </strong>

                            <p>
                                Jadwal sesi foto belum ditetapkan
                                untuk booking ini.
                            </p>
                        </div>

                    </div>

                    @endforelse

                </div>

            </div>


                {{-- RATING DAN ULASAN --}}
                <div class="booking-detail-card testimonial-card">

                    <div class="detail-card-header">
                        <div>
                            <span class="booking-label">
                                Pengalaman Anda
                            </span>
                            <h2>Rating & Komentar</h2>
                        </div>

                        <i class="bi bi-star-fill"></i>
                    </div>

                    @if (session('success'))
                        <div class="testimonial-alert testimonial-alert-success"
                            role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="testimonial-alert testimonial-alert-error"
                            role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="testimonial-alert testimonial-alert-error"
                            role="alert">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if ($booking->testimonial)

                        {{-- RIWAYAT ULASAN YANG SUDAH DIKIRIM --}}
                        <div class="testimonial-existing">

                            <div class="testimonial-display-stars"
                                aria-label="Rating {{ $booking->testimonial->rating }} dari 5">

                                @for ($star = 1; $star <= 5; $star++)
                                    <span class="{{ $star <= $booking->testimonial->rating ? 'is-active' : '' }}">★</span>
                                @endfor

                                <strong>
                                    {{ $booking->testimonial->rating }}/5
                                </strong>
                            </div>

                            <p class="testimonial-comment">
                                {{ $booking->testimonial->comment }}
                            </p>

                            <div class="testimonial-history-meta">
                                <span>
                                    Dikirim
                                    {{ $booking->testimonial->created_at?->format('d M Y, H:i') ?? '-' }}
                                </span>

                                <span class="testimonial-status testimonial-status-{{ $booking->testimonial->status }}">
                                    @if ($booking->testimonial->status === 'pending')
                                        Menunggu Persetujuan
                                    @elseif ($booking->testimonial->status === 'published')
                                        Dipublikasikan
                                    @else
                                        Disembunyikan dari Publik
                                    @endif
                                </span>
                            </div>

                        </div>

                    @elseif ($booking->status === 'completed')

                        {{-- FORMULIR RATING --}}
                        <p class="testimonial-description">
                            Terima kasih telah memilih Aura Studio.
                            Bagikan pengalaman Anda agar kami bisa terus berkembang.
                        </p>

                        <form
                            action="{{ route('customer.bookings.testimonial.store', $booking) }}"
                            method="POST"
                            class="testimonial-form"
                        >
                            @csrf

                            <label class="testimonial-form-label">
                                Berikan Rating
                            </label>

                            <div class="testimonial-star-rating"
                                role="radiogroup"
                                aria-label="Pilih rating bintang">

                                @for ($star = 5; $star >= 1; $star--)
                                    <input
                                        type="radio"
                                        id="testimonial-rating-{{ $star }}"
                                        name="rating"
                                        value="{{ $star }}"
                                        {{ old('rating') == $star ? 'checked' : '' }}
                                        required
                                    >

                                    <label
                                        for="testimonial-rating-{{ $star }}"
                                        title="{{ $star }} bintang"
                                        aria-label="{{ $star }} bintang"
                                    >★</label>
                                @endfor

                            </div>

                            <label
                                for="testimonial-comment"
                                class="testimonial-form-label"
                            >
                                Komentar Anda
                            </label>

                            <textarea
                                id="testimonial-comment"
                                name="comment"
                                rows="5"
                                minlength="5"
                                maxlength="1000"
                                placeholder="Ceritakan pengalaman Anda menggunakan layanan Aura Studio..."
                                required
                            >{{ old('comment') }}</textarea>

                            <p class="testimonial-helper">
                                Komentar minimal 5 dan maksimal 1.000 karakter.
                                Ulasan akan ditinjau oleh admin sebelum ditampilkan secara publik.
                            </p>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i>
                                Kirim Rating
                            </button>

                        </form>

                    @else

                        <div class="testimonial-waiting">
                            <i class="bi bi-hourglass-split"></i>

                            <div>
                                <strong>Rating belum tersedia</strong>
                                <p>
                                    Anda dapat memberikan rating setelah
                                    booking ditandai selesai oleh admin.
                                </p>
                            </div>
                        </div>

                    @endif

                </div>



            {{-- INVOICE --}}
            <aside class="booking-invoice">

                <div class="booking-detail-card invoice-card">

                    <div class="detail-card-header">

                        <div>
                            <span class="booking-label">
                                Ringkasan
                            </span>

                            <h2>Invoice</h2>
                        </div>

                        <i class="bi bi-receipt"></i>

                    </div>


                    <div class="invoice-row">

                        <span>Kode Booking</span>

                        <strong>
                            {{ $booking->booking_code }}
                        </strong>

                    </div>


                    <div class="invoice-row">

                        <span>Paket</span>

                        <strong>
                            {{ $booking->package->name ?? '-' }}
                        </strong>

                    </div>


                    <div class="invoice-row">

                        <span>Jumlah Orang</span>

                        <strong>
                            {{ $booking->number_of_people }}
                        </strong>

                    </div>


                    <div class="invoice-row invoice-total">

                        <span>Total</span>

                        <strong>
                            Rp {{ number_format(
                                $booking->total_price,
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>

                    </div>


                    <div class="invoice-payment">

                        <span class="booking-label">
                            Status Pembayaran
                        </span>

                        @if ($booking->payment)

                        <span class="
                                payment-status
                                payment-status-{{ $booking->payment->status }}
                            ">
                            {{ ucfirst($booking->payment->status) }}
                        </span>

                        <div class="payment-meta">

                            <span>Metode</span>

                            <strong>
                                {{ $booking->payment->payment_method }}
                            </strong>

                        </div>


                        <div class="payment-meta">

                            <span>Nominal</span>

                            <strong>
                                Rp {{ number_format(
                                        $booking->payment->amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                            </strong>

                        </div>


                        @if (
                        $booking->payment->status === 'rejected' &&
                        $booking->payment->rejection_reason
                        )

                        <div class="payment-rejection">

                            <strong>
                                Pembayaran ditolak
                            </strong>

                            <p>
                                {{ $booking->payment->rejection_reason }}
                            </p>

                        </div>

                        @endif

                        @else

                        <span class="payment-status payment-status-unpaid">
                            Belum Dibayar
                        </span>

                        <p class="invoice-help">
                            Belum ada pembayaran untuk booking ini.
                        </p>

                        <a
                            href="{{ route('customer.payments.create', $booking) }}"
                            class="btn btn-primary invoice-pay-button">
                            Bayar Sekarang
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        @endif

                    </div>

                </div>

            </aside>

        </div>

    </div>

</section>

@endsection