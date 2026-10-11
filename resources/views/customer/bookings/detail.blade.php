@extends('layouts.booking')

@section('title', 'Detail Pemesanan — Aura Studio')

@php
    $currentStep = 4;

    $numberOfPeople = old(
        'number_of_people',
        $draft['number_of_people'] ?? 1
    );

    $locationAddress = old(
        'location_address',
        $draft['location_address'] ?? ''
    );

    $locationNotes = old(
        'location_notes',
        $draft['location_notes'] ?? ''
    );

    $notes = old(
        'notes',
        $draft['notes'] ?? ''
    );
@endphp


@section('content')

<section class="booking-page">

    <div class="booking-page-heading">

        <span class="booking-eyebrow">
            LANGKAH KEEMPAT
        </span>

        <h1>
            Lengkapi detail
            <em>pemesananmu.</em>
        </h1>

        <p>
            Pastikan data pemesan dan kebutuhan sesi
            sudah sesuai sebelum melanjutkan ke tahap konfirmasi.
        </p>

    </div>


    @if ($errors->any())

        <div class="booking-alert booking-alert-error">

            <i class="bi bi-exclamation-circle"></i>

            <div>

                <strong>
                    Ada data yang perlu diperiksa.
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
        action="{{ route('customer.bookings.detail.store') }}"
        method="POST"
    >
        @csrf


        <div class="booking-step-layout">

            <div class="booking-detail-content">


                {{-- DATA PEMESAN --}}
                <section class="booking-detail-card">

                    <div class="booking-detail-card-heading">

                        <span class="booking-section-number">
                            01
                        </span>

                        <div>
                            <h2>Data Pemesan</h2>

                            <p>
                                Data ini diambil dari akun customer
                                yang sedang digunakan.
                            </p>
                        </div>

                    </div>


                    <div class="booking-form-grid">

                        <div class="booking-field">

                            <label>Nama Lengkap</label>

                            <div class="booking-readonly-field">
                                <i class="bi bi-person"></i>

                                <span>
                                    {{ $customer->name }}
                                </span>
                            </div>

                        </div>


                        <div class="booking-field">

                            <label>Email</label>

                            <div class="booking-readonly-field">
                                <i class="bi bi-envelope"></i>

                                <span>
                                    {{ $customer->email }}
                                </span>
                            </div>

                        </div>


                        <div class="booking-field booking-field-full">

                            <label>Nomor Telepon</label>

                            <div class="booking-readonly-field">
                                <i class="bi bi-telephone"></i>

                                <span>
                                    {{ $customer->phone ?: 'Belum tersedia pada profil' }}
                                </span>
                            </div>

                        </div>

                    </div>

                </section>


                {{-- DETAIL SESI --}}
                <section class="booking-detail-card">

                    <div class="booking-detail-card-heading">

                        <span class="booking-section-number">
                            02
                        </span>

                        <div>
                            <h2>Detail Sesi</h2>

                            <p>
                                Beri tahu kami jumlah peserta
                                dan kebutuhan khusus untuk sesi ini.
                            </p>
                        </div>

                    </div>


                    <div class="booking-form-grid">

                        <div class="booking-field">

                            <label for="number_of_people">
                                Jumlah Peserta
                                <span>*</span>
                            </label>

                            <div class="booking-input-with-icon">

                                <i class="bi bi-people"></i>

                                <input
                                    type="number"
                                    name="number_of_people"
                                    id="number_of_people"
                                    min="1"
                                    @if ($package->max_people)
                                        max="{{ $package->max_people }}"
                                    @endif
                                    value="{{ $numberOfPeople }}"
                                    required
                                >

                            </div>

                            <small>
                                @if ($package->max_people)
                                    Maksimal {{ $package->max_people }}
                                    orang untuk paket ini.
                                @else
                                    Masukkan jumlah peserta sesi.
                                @endif
                            </small>

                        </div>


                        <div class="booking-field">

                            <label>
                                Durasi Sesi
                            </label>

                            <div class="booking-readonly-field">
                                <i class="bi bi-clock"></i>

                                <span>
                                    {{ $package->duration_minutes }}
                                    menit
                                </span>
                            </div>

                        </div>


                        <div class="booking-field booking-field-full">

                            <label for="notes">
                                Catatan / Kebutuhan Sesi
                            </label>

                            <textarea
                                name="notes"
                                id="notes"
                                rows="5"
                                placeholder="Contoh: konsep foto formal, membawa properti sendiri, kebutuhan khusus, dan sebagainya."
                            >{{ $notes }}</textarea>

                            <small>
                                Opsional. Maksimal 1000 karakter.
                            </small>

                        </div>

                    </div>

                </section>


                {{-- ON LOCATION --}}
                @if ($service->is_on_location)

                    <section class="booking-detail-card booking-location-card">

                        <div class="booking-detail-card-heading">

                            <span class="booking-section-number">
                                03
                            </span>

                            <div>
                                <h2>Lokasi Pemotretan</h2>

                                <p>
                                    Layanan ini dilakukan di lokasi
                                    pilihan customer.
                                </p>
                            </div>

                        </div>


                        <div class="booking-form-grid">

                            <div class="booking-field booking-field-full">

                                <label for="location_address">
                                    Alamat Lengkap
                                    <span>*</span>
                                </label>

                                <textarea
                                    name="location_address"
                                    id="location_address"
                                    rows="4"
                                    required
                                    placeholder="Masukkan alamat lengkap lokasi pemotretan."
                                >{{ $locationAddress }}</textarea>

                            </div>


                            <div class="booking-field booking-field-full">

                                <label for="location_notes">
                                    Catatan Lokasi
                                </label>

                                <textarea
                                    name="location_notes"
                                    id="location_notes"
                                    rows="3"
                                    placeholder="Contoh: patokan lokasi, akses masuk, titik pertemuan, atau informasi lainnya."
                                >{{ $locationNotes }}</textarea>

                            </div>

                        </div>

                    </section>

                @endif


                <div class="booking-detail-info">

                    <i class="bi bi-info-circle"></i>

                    <p>
                        Detail masih dapat diperiksa kembali
                        pada langkah konfirmasi sebelum booking dibuat.
                    </p>

                </div>

            </div>


            {{-- SUMMARY --}}
            <aside class="booking-summary">

                <div class="booking-summary-card">

                    <span class="booking-summary-eyebrow">
                        RINGKASAN BOOKING
                    </span>

                    <h3>Reservasimu</h3>


                    <div class="booking-summary-list">

                        <div class="summary-row">
                            <span>Layanan</span>

                            <strong>
                                {{ $service->name }}
                            </strong>
                        </div>


                        <div class="summary-row">
                            <span>Cabang</span>

                            <strong>
                                {{ $branch->name }}
                            </strong>
                        </div>


                        <div class="summary-row">
                            <span>Paket</span>

                            <strong>
                                {{ $package->name }}
                            </strong>
                        </div>


                        <div class="summary-row">
                            <span>Tanggal</span>

                            <strong>
                                {{ $schedule->schedule_date
                                    ->locale('id')
                                    ->translatedFormat('l, d F Y') }}
                            </strong>
                        </div>


                        <div class="summary-row">
                            <span>Waktu</span>

                            <strong>
                                {{ \Carbon\Carbon::parse(
                                    $schedule->start_time
                                )->format('H:i') }}

                                –

                                {{ \Carbon\Carbon::parse(
                                    $schedule->end_time
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


                        @if ($service->requires_room)

                            <div class="summary-row">
                                <span>Studio Room</span>

                                <strong>
                                    {{ $schedule->studioRoom?->name
                                        ?? 'Menyesuaikan jadwal' }}
                                </strong>
                            </div>

                        @endif

                    </div>


                    <div class="summary-total">

                        <span>Total Paket</span>

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
                        Lanjut Konfirmasi

                        <i class="bi bi-arrow-right"></i>
                    </button>


                    <a
                        href="{{ route(
                            'customer.bookings.schedule'
                        ) }}"
                        class="booking-back-link"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Kembali Pilih Jadwal
                    </a>

                </div>

            </aside>

        </div>

    </form>

</section>

@endsection