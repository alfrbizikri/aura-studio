@extends('layouts.booking')

@section('title', 'Konfirmasi Booking — Aura Studio')

@php
    $currentStep = 5;
@endphp

@section('content')

<section class="booking-page">

    <div class="booking-page-heading">

        <span class="booking-eyebrow">
            LANGKAH KELIMA
        </span>

        <h1>
            Periksa kembali
            <em>reservasimu.</em>
        </h1>

        <p>
            Pastikan seluruh detail sudah sesuai.
            Setelah dikonfirmasi, jadwal akan diamankan
            khusus untuk booking ini.
        </p>

    </div>


    @if ($errors->any())

        <div class="booking-alert booking-alert-error">

            <i class="bi bi-exclamation-circle"></i>

            <div>

                <strong>
                    Booking belum dapat dikonfirmasi.
                </strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    <form
        action="{{ route('customer.bookings.store') }}"
        method="POST"
    >
        @csrf


        <div class="booking-step-layout">

            <div class="confirmation-content">


                {{-- HERO CONFIRMATION --}}
                <section class="confirmation-intro-card">

                    <span class="confirmation-intro-icon">
                        <i class="bi bi-check2-circle"></i>
                    </span>

                    <div>

                        <span class="booking-summary-eyebrow">
                            SIAP DIKONFIRMASI
                        </span>

                        <h2>
                            Semua detail sudah lengkap.
                        </h2>

                        <p>
                            Periksa ringkasan berikut sebelum
                            Aura Studio membuat bookingmu.
                        </p>

                    </div>

                </section>


                {{-- PILIHAN UTAMA --}}
                <section class="confirmation-card">

                    <div class="confirmation-card-heading">

                        <span class="booking-section-number">
                            01
                        </span>

                        <div>
                            <h2>Detail Reservasi</h2>
                            <p>Layanan dan paket yang dipilih.</p>
                        </div>

                    </div>


                    <div class="confirmation-grid">

                        <div class="confirmation-item">
                            <span>Layanan</span>
                            <strong>{{ $service->name }}</strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Cabang</span>
                            <strong>{{ $branch->name }}</strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Paket</span>
                            <strong>{{ $package->name }}</strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Durasi</span>
                            <strong>
                                {{ $package->duration_minutes }} menit
                            </strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Jumlah Peserta</span>
                            <strong>
                                {{ $draft['number_of_people'] }} orang
                            </strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Total Harga</span>
                            <strong class="confirmation-price">
                                Rp {{ number_format(
                                    $package->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>
                        </div>

                    </div>

                </section>


                {{-- JADWAL --}}
                <section class="confirmation-card">

                    <div class="confirmation-card-heading">

                        <span class="booking-section-number">
                            02
                        </span>

                        <div>
                            <h2>Jadwal Sesi</h2>
                            <p>Waktu dan tim yang menangani sesi.</p>
                        </div>

                    </div>


                    <div class="confirmation-schedule">

                        <div class="confirmation-date-box">

                            <small>
                                {{ $schedule->schedule_date
                                    ->locale('id')
                                    ->translatedFormat('M') }}
                            </small>

                            <strong>
                                {{ $schedule->schedule_date->format('d') }}
                            </strong>

                            <span>
                                {{ $schedule->schedule_date
                                    ->locale('id')
                                    ->translatedFormat('Y') }}
                            </span>

                        </div>


                        <div class="confirmation-schedule-copy">

                            <h3>
                                {{ $schedule->schedule_date
                                    ->locale('id')
                                    ->translatedFormat('l, d F Y') }}
                            </h3>

                            <p>
                                <i class="bi bi-clock"></i>

                                {{ \Carbon\Carbon::parse(
                                    $schedule->start_time
                                )->format('H:i') }}

                                –

                                {{ \Carbon\Carbon::parse(
                                    $schedule->end_time
                                )->format('H:i') }}

                                WIB
                            </p>

                            <p>
                                <i class="bi bi-person"></i>

                                {{ $schedule->photographer?->name
                                    ?? 'Tidak memerlukan fotografer' }}
                            </p>

                            @if ($service->requires_room)

                                <p>
                                    <i class="bi bi-door-open"></i>

                                    {{ $schedule->studioRoom?->name
                                        ?? 'Ruangan menyesuaikan' }}
                                </p>

                            @endif

                        </div>

                    </div>

                </section>


                {{-- CUSTOMER --}}
                <section class="confirmation-card">

                    <div class="confirmation-card-heading">

                        <span class="booking-section-number">
                            03
                        </span>

                        <div>
                            <h2>Data Pemesan</h2>
                            <p>Informasi customer dan catatan sesi.</p>
                        </div>

                    </div>


                    <div class="confirmation-grid">

                        <div class="confirmation-item">
                            <span>Nama</span>
                            <strong>{{ $customer->name }}</strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Email</span>
                            <strong>{{ $customer->email }}</strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Telepon</span>
                            <strong>
                                {{ $customer->phone ?: 'Belum tersedia' }}
                            </strong>
                        </div>

                        <div class="confirmation-item">
                            <span>Jumlah Peserta</span>
                            <strong>
                                {{ $draft['number_of_people'] }} orang
                            </strong>
                        </div>

                    </div>


                    @if (!empty($draft['notes']))

                        <div class="confirmation-note">

                            <span>Catatan Sesi</span>

                            <p>
                                {{ $draft['notes'] }}
                            </p>

                        </div>

                    @endif


                    @if ($service->is_on_location)

                        <div class="confirmation-location">

                            <span>
                                <i class="bi bi-geo-alt"></i>
                                Lokasi Pemotretan
                            </span>

                            <strong>
                                {{ $draft['location_address'] }}
                            </strong>

                            @if (!empty($draft['location_notes']))
                                <p>
                                    {{ $draft['location_notes'] }}
                                </p>
                            @endif

                        </div>

                    @endif

                </section>


                {{-- CONSENT --}}
                <label class="confirmation-consent">

                    <input
                        type="checkbox"
                        name="confirmation"
                        value="1"
                        {{ old('confirmation') ? 'checked' : '' }}
                    >

                    <span class="confirmation-checkbox"></span>

                    <span>
                        Saya telah memeriksa seluruh detail booking
                        dan menyatakan bahwa data di atas sudah benar.
                        Saya memahami bahwa jadwal akan ditandai
                        sebagai terpesan setelah konfirmasi.
                    </span>

                </label>

            </div>


            {{-- SUMMARY --}}
            <aside class="booking-summary">

                <div class="booking-summary-card">

                    <span class="booking-summary-eyebrow">
                        KONFIRMASI AKHIR
                    </span>

                    <h3>Reservasimu</h3>


                    <div class="booking-summary-list">

                        <div class="summary-row">
                            <span>Paket</span>
                            <strong>
                                {{ $package->name }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Cabang</span>
                            <strong>
                                {{ $branch->name }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Tanggal</span>
                            <strong>
                                {{ $schedule->schedule_date
                                    ->locale('id')
                                    ->translatedFormat('d F Y') }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Waktu</span>
                            <strong>
                                {{ \Carbon\Carbon::parse(
                                    $schedule->start_time
                                )->format('H:i') }}
                                WIB
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Fotografer</span>
                            <strong>
                                {{ $schedule->photographer?->name
                                    ?? 'Tidak diperlukan' }}
                            </strong>
                        </div>

                    </div>


                    <div class="summary-total">

                        <span>Total Pembayaran</span>

                        <strong>
                            Rp {{ number_format(
                                $package->price,
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>

                    </div>


                    <button
                        type="submit"
                        class="booking-next-button"
                    >
                        Konfirmasi Booking

                        <i class="bi bi-check2-circle"></i>
                    </button>


                    <a
                        href="{{ route(
                            'customer.bookings.detail'
                        ) }}"
                        class="booking-back-link"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Kembali Ubah Detail
                    </a>


                    <p class="summary-note">
                        <i class="bi bi-lock"></i>

                        Jadwal akan diamankan setelah
                        booking berhasil dibuat.
                    </p>

                </div>

            </aside>

        </div>

    </form>

</section>

@endsection