@extends('layouts.admin')

@section('title', 'Detail Pembayaran - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.payments.index') }}"
            class="admin-back-link"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <span class="admin-label">
            TRANSAKSI
        </span>

        <h1>
            Detail Pembayaran
        </h1>

        <p>
            Periksa pembayaran untuk booking
            <strong>
                {{ $payment->booking?->booking_code ?? '-' }}
            </strong>.
        </p>

    </div>

</div>


{{-- PESAN BERHASIL --}}
@if (session('success'))

    <div class="admin-alert success">

        <i class="bi bi-check-circle"></i>

        {{ session('success') }}

    </div>

@endif


{{-- PESAN ERROR --}}
@if (session('error'))

    <div class="admin-alert error">

        <i class="bi bi-exclamation-circle"></i>

        {{ session('error') }}

    </div>

@endif


{{-- INFORMASI PEMBAYARAN --}}
<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Pembayaran
        </h2>

        <p>
            Data pembayaran yang dikirim oleh customer.
        </p>

    </div>


    <div class="admin-form-grid">

        <div class="admin-form-group">

            <label>Kode Booking</label>

            <strong>
                {{ $payment->booking?->booking_code ?? '-' }}
            </strong>

        </div>


        <div class="admin-form-group">

            <label>Customer</label>

            <span>
                {{ $payment->booking?->user?->name ?? '-' }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Email</label>

            <span>
                {{ $payment->booking?->user?->email ?? '-' }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Paket</label>

            <span>
                {{ $payment->booking?->package?->name ?? '-' }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Metode Pembayaran</label>

            <span>

                @if ($payment->payment_method === 'bank_transfer')
                    Transfer Bank
                @elseif ($payment->payment_method === 'e_wallet')
                    E-Wallet
                @else
                    {{ $payment->payment_method }}
                @endif

            </span>

        </div>


        <div class="admin-form-group">

            <label>Nominal</label>

            <strong>

                Rp {{ number_format(
                    $payment->amount,
                    0,
                    ',',
                    '.'
                ) }}

            </strong>

        </div>


        <div class="admin-form-group">

            <label>Status Pembayaran</label>

            <div>

                @if ($payment->status === 'pending')

                    <span class="admin-status">
                        Menunggu Verifikasi
                    </span>

                @elseif ($payment->status === 'paid')

                    <span class="admin-status active">
                        Dibayar
                    </span>

                @elseif ($payment->status === 'rejected')

                    <span class="admin-status inactive">
                        Ditolak
                    </span>

                @else

                    <span class="admin-status inactive">
                        Refund
                    </span>

                @endif

            </div>

        </div>


        <div class="admin-form-group">

            <label>Tanggal Pembayaran</label>

            <span>
                {{ $payment->created_at?->format('d/m/Y H:i') ?? '-' }}
            </span>

        </div>

    </div>

</div>


{{-- BUKTI PEMBAYARAN --}}
<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Bukti Pembayaran
        </h2>

        <p>
            Periksa bukti pembayaran sebelum melakukan verifikasi.
        </p>

    </div>


    @if ($payment->proof_of_payment)

        <div style="margin-top: 20px;">

            <a
                href="{{ asset(
                    'storage/' . $payment->proof_of_payment
                ) }}"
                target="_blank"
            >

                <img
                    src="{{ asset(
                        'storage/' . $payment->proof_of_payment
                    ) }}"
                    alt="Bukti Pembayaran"
                    style="
                        width: 100%;
                        max-width: 600px;
                        height: auto;
                        border-radius: 12px;
                    "
                >

            </a>

        </div>

    @else

        <div class="admin-empty">
            Bukti pembayaran tidak tersedia.
        </div>

    @endif

</div>


{{-- DETAIL BOOKING --}}
<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Booking
        </h2>

        <p>
            Booking yang terkait dengan pembayaran ini.
        </p>

    </div>


    <div class="admin-form-grid">

        <div class="admin-form-group">

            <label>Status Booking</label>

            <span>
                {{ ucfirst(
                    $payment->booking?->status ?? '-'
                ) }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Total Booking</label>

            <strong>

                @if ($payment->booking)

                    Rp {{ number_format(
                        $payment->booking->total_price,
                        0,
                        ',',
                        '.'
                    ) }}

                @else
                    -
                @endif

            </strong>

        </div>


        @if ($payment->booking)

            <div class="admin-form-group full">

                <a
                    href="{{ route(
                        'admin.bookings.show',
                        $payment->booking->id
                    ) }}"
                    class="admin-btn-secondary"
                >
                    <i class="bi bi-eye"></i>
                    Lihat Detail Booking
                </a>

            </div>

        @endif

    </div>

</div>


{{-- VERIFIKASI --}}
@if ($payment->status === 'pending')

    <div class="admin-form-card">

        <div class="admin-form-title">

            <h2>
                Verifikasi Pembayaran
            </h2>

            <p>
                Terima pembayaran jika bukti valid atau tolak jika tidak sesuai.
            </p>

        </div>


        {{-- TERIMA --}}
        <form
            action="{{ route(
                'admin.payments.approve',
                $payment->id
            ) }}"
            method="POST"
            onsubmit="return confirm(
                'Yakin ingin menerima pembayaran ini?'
            )"
        >

            @csrf
            @method('PATCH')

            <div class="admin-form-actions">

                <button
                    type="submit"
                    class="admin-btn-primary"
                >
                    <i class="bi bi-check-circle"></i>
                    Terima Pembayaran
                </button>

            </div>

        </form>


        <hr style="margin: 30px 0;">


        {{-- TOLAK --}}
        <form
            action="{{ route(
                'admin.payments.reject',
                $payment->id
            ) }}"
            method="POST"
        >

            @csrf
            @method('PATCH')


            <div class="admin-form-group full">

                <label for="rejection_reason">
                    Alasan Penolakan <span>*</span>
                </label>

                <textarea
                    id="rejection_reason"
                    name="rejection_reason"
                    rows="4"
                    placeholder="Contoh: Bukti transfer tidak jelas atau nominal tidak sesuai."
                    required
                >{{ old('rejection_reason') }}</textarea>

                @error('rejection_reason')

                    <small class="admin-field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            <div class="admin-form-actions">

                <button
                    type="submit"
                    class="admin-btn-secondary"
                    onclick="return confirm(
                        'Yakin ingin menolak pembayaran ini?'
                    )"
                >
                    <i class="bi bi-x-circle"></i>
                    Tolak Pembayaran
                </button>

            </div>

        </form>

    </div>

@endif


{{-- HASIL VERIFIKASI --}}
@if ($payment->status !== 'pending')

    <div class="admin-form-card">

        <div class="admin-form-title">

            <h2>
                Hasil Verifikasi
            </h2>

        </div>


        <div class="admin-form-grid">

            <div class="admin-form-group">

                <label>Status</label>

                <strong>
                    {{ ucfirst($payment->status) }}
                </strong>

            </div>


            <div class="admin-form-group">

                <label>Waktu Verifikasi</label>

                <span>

                    {{ $payment->verified_at
                        ? $payment->verified_at->format('d/m/Y H:i')
                        : '-'
                    }}

                </span>

            </div>


            @if ($payment->verifier)

                <div class="admin-form-group">

                    <label>Diverifikasi Oleh</label>

                    <span>
                        {{ $payment->verifier->name }}
                    </span>

                </div>

            @endif


            @if ($payment->status === 'rejected')

                <div class="admin-form-group full">

                    <label>Alasan Penolakan</label>

                    <span>
                        {{ $payment->rejection_reason ?? '-' }}
                    </span>

                </div>

            @endif

        </div>

    </div>

@endif

@endsection