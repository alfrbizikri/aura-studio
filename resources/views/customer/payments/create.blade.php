@extends('layouts.booking')

@section('title', 'Pembayaran — Aura Studio')

@php
    $currentStep = 6;

    $schedule = $booking->schedules->first();
@endphp

@section('content')

    <section class="booking-page payment-step-page">

        <div class="payment-success-head">

            <span class="payment-success-icon">
                <i class="bi bi-check-lg"></i>
            </span>

            <div>

                <span class="booking-eyebrow">
                    LANGKAH TERAKHIR
                </span>

                <h1>
                    Booking berhasil.
                    <em>Lengkapi pembayaranmu.</em>
                </h1>

                <p>
                    Jadwalmu sudah diamankan.
                    Kirim bukti pembayaran agar admin dapat
                    melakukan verifikasi.
                </p>

            </div>

        </div>


        @if (session('success'))

            <div class="payment-step-alert success">

                <i class="bi bi-check-circle"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        @if ($isResubmission)

            <div class="payment-step-alert rejected">

                <i class="bi bi-exclamation-triangle"></i>

                <div>

                    <strong>
                        Pembayaran sebelumnya ditolak.
                    </strong>

                    @if ($payment->rejection_reason)

                        <p>
                            Alasan:
                            {{ $payment->rejection_reason }}
                        </p>

                    @endif

                    <span>
                        Silakan unggah bukti pembayaran baru.
                    </span>

                </div>

            </div>

        @endif


        @if ($errors->any())

            <div class="booking-alert booking-alert-error">

                <i class="bi bi-exclamation-circle"></i>

                <div>

                    <strong>
                        Periksa kembali data pembayaran.
                    </strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

        @endif


        <div class="booking-step-layout">


            {{-- LEFT --}}
            <div class="payment-step-content">


                {{-- BOOKING CODE --}}
                <section class="payment-booking-ticket">

                    <div>

                        <span>
                            KODE BOOKING
                        </span>

                        <strong>
                            {{ $booking->booking_code }}
                        </strong>

                        <p>
                            Simpan kode ini untuk memudahkan
                            pengecekan reservasi.
                        </p>

                    </div>


                    <div class="payment-ticket-total">

                        <span>
                            Total Pembayaran
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

                </section>


                {{-- INSTRUCTION --}}
                <section class="payment-step-card">

                    <div class="payment-step-heading">

                        <span class="booking-section-number">
                            01
                        </span>

                        <div>

                            <h2>
                                Pilih Metode Pembayaran
                            </h2>

                            <p>
                                Pilih metode yang kamu gunakan
                                untuk melakukan pembayaran.
                            </p>

                        </div>

                    </div>


                    {{-- INFORMASI TUJUAN PEMBAYARAN --}}
                    <div class="payment-transfer-info">

                        <div class="payment-transfer-head">

                            <div class="payment-transfer-bank">
                                <span class="payment-transfer-icon">
                                    <i class="bi bi-bank"></i>
                                </span>

                                <div>
                                    <small>TRANSFER BANK</small>
                                    <strong>Bank Central Asia (BCA)</strong>
                                </div>
                            </div>

                            <span class="payment-demo-badge">
                                Demo
                            </span>

                        </div>


                        <div class="payment-transfer-account">

                            <div>
                                <span>Nomor Rekening</span>

                                <strong>
                                    1234 5678 9000
                                </strong>
                            </div>

                            <button type="button" class="payment-copy-button"
                                onclick="navigator.clipboard.writeText('123456789000')">
                                <i class="bi bi-copy"></i>
                                Salin
                            </button>

                        </div>


                        <div class="payment-transfer-holder">

                            <span>Atas Nama</span>

                            <strong>
                                PT Aura Studio Kreatif
                            </strong>

                        </div>

                    </div>


                    <div class="payment-nominal-box">

                        <div>
                            <span>NOMINAL YANG HARUS DIBAYAR</span>

                            <strong>
                                Rp {{ number_format(
                                    $booking->total_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>
                        </div>

                        <button type="button" class="payment-copy-button"
                            onclick="navigator.clipboard.writeText('{{ (int) $booking->total_price }}')">
                            <i class="bi bi-copy"></i>
                            Salin Nominal
                        </button>

                    </div>


                    <div class="payment-transfer-note">

                        <i class="bi bi-info-circle"></i>

                        <p>
                            Transfer sesuai nominal yang tertera,
                            kemudian unggah bukti pembayaran pada
                            bagian di bawah ini.
                            Informasi rekening di atas masih berupa
                            data demo untuk pengembangan sistem.
                        </p>

                    </div>


                    <form action="{{ route(
                        'customer.payments.store',
                        $booking
                    ) }}" method="POST" enctype="multipart/form-data" id="paymentForm">

                        @csrf


                        <div class="payment-method-grid">

                            <label
                                class="payment-method-option
                                {{ old('payment_method', $payment?->payment_method) === 'bank_transfer' ? 'selected' : '' }}">

                                <input type="radio" name="payment_method" value="bank_transfer" @checked(
                                    old(
                                        'payment_method',
                                        $payment?->payment_method
                                    ) === 'bank_transfer'
                                )>

                                <span class="payment-method-icon">
                                    <i class="bi bi-bank"></i>
                                </span>

                                <span>

                                    <strong>
                                        Bank Transfer
                                    </strong>

                                    <small>
                                        Transfer melalui rekening bank.
                                    </small>

                                </span>

                                <span class="payment-method-check">
                                    <i class="bi bi-check-lg"></i>
                                </span>

                            </label>


                            <label class="payment-method-option
                                {{ old('payment_method', $payment?->payment_method) === 'e_wallet' ? 'selected' : '' }}">

                                <input type="radio" name="payment_method" value="e_wallet" @checked(
                                    old(
                                        'payment_method',
                                        $payment?->payment_method
                                    ) === 'e_wallet'
                                )>

                                <span class="payment-method-icon">
                                    <i class="bi bi-wallet2"></i>
                                </span>

                                <span>

                                    <strong>
                                        E-Wallet
                                    </strong>

                                    <small>
                                        Pembayaran melalui dompet digital.
                                    </small>

                                </span>

                                <span class="payment-method-check">
                                    <i class="bi bi-check-lg"></i>
                                </span>

                            </label>

                        </div>


                        <div class="payment-step-heading payment-upload-heading">

                            <span class="booking-section-number">
                                02
                            </span>

                            <div>

                                <h2>
                                    Upload Bukti Pembayaran
                                </h2>

                                <p>
                                    Unggah screenshot atau foto bukti
                                    transaksi yang jelas.
                                </p>

                            </div>

                        </div>


                        <label class="payment-upload-box" for="proof_of_payment" id="paymentUploadBox">

                            <span class="payment-upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </span>

                            <strong>
                                Pilih bukti pembayaran
                            </strong>

                            <span>
                                JPG, JPEG, PNG atau WebP · Maksimal 5 MB
                            </span>

                            <em id="paymentFileName">
                                Belum ada file dipilih
                            </em>

                            <input type="file" name="proof_of_payment" id="proof_of_payment" accept=".jpg,.jpeg,.png,.webp"
                                required>

                        </label>


                        <button type="submit" class="booking-next-button payment-final-button">

                            <i class="bi bi-shield-check"></i>

                            {{ $isResubmission
        ? 'Kirim Ulang Bukti Pembayaran'
        : 'Kirim Bukti Pembayaran' }}

                        </button>


                        <p class="payment-verification-note">

                            <i class="bi bi-clock-history"></i>

                            Setelah dikirim, pembayaran akan berstatus
                            <strong>Pending</strong>
                            sampai diverifikasi oleh admin.

                        </p>

                    </form>

                </section>

            </div>


            {{-- SUMMARY --}}
            <aside class="booking-summary">

                <div class="booking-summary-card">

                    <span class="booking-summary-eyebrow">
                        RINGKASAN RESERVASI
                    </span>

                    <h3>
                        Detail Booking
                    </h3>


                    <div class="booking-summary-list">

                        <div class="summary-row">

                            <span>Kode Booking</span>

                            <strong>
                                {{ $booking->booking_code }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>Layanan</span>

                            <strong>
                                {{ $booking->package->service->name ?? '-' }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>Paket</span>

                            <strong>
                                {{ $booking->package->name ?? '-' }}
                            </strong>

                        </div>


                        @if ($schedule)

                                            <div class="summary-row">

                                                <span>Cabang</span>

                                                <strong>
                                                    {{ $schedule->branch?->name ?? '-' }}
                                                </strong>

                                            </div>


                                            <div class="summary-row">

                                                <span>Tanggal</span>

                                                <strong>
                                                    {{ $schedule->schedule_date
                                ->locale('id')
                                ->translatedFormat(
                                    'd F Y'
                                ) }}
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

                        @endif

                    </div>


                    <div class="summary-total">

                        <span>
                            Total Pembayaran
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


                    <a href="{{ route(
        'customer.bookings.show',
        $booking
    ) }}" class="booking-back-link">

                        <i class="bi bi-receipt"></i>

                        Lihat Detail Booking

                    </a>


                    <p class="summary-note">

                        <i class="bi bi-lock"></i>

                        Data pembayaranmu digunakan hanya
                        untuk proses verifikasi reservasi.

                    </p>

                </div>

            </aside>

        </div>

    </section>

@endsection


@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const methodOptions = [
                ...document.querySelectorAll(
                    '.payment-method-option'
                )
            ];

            const fileInput =
                document.getElementById('proof_of_payment');

            const fileName =
                document.getElementById('paymentFileName');

            const uploadBox =
                document.getElementById('paymentUploadBox');


            methodOptions.forEach(function (option) {

                const radio =
                    option.querySelector(
                        'input[type="radio"]'
                    );

                radio.addEventListener('change', function () {

                    methodOptions.forEach(function (item) {
                        item.classList.remove('selected');
                    });

                    if (radio.checked) {
                        option.classList.add('selected');
                    }

                });

            });


            fileInput.addEventListener('change', function () {

                if (
                    fileInput.files
                    && fileInput.files.length
                ) {
                    fileName.textContent =
                        fileInput.files[0].name;

                    uploadBox.classList.add('has-file');

                    return;
                }

                fileName.textContent =
                    'Belum ada file dipilih';

                uploadBox.classList.remove('has-file');

            });

        });
    </script>

@endpush