@extends('layouts.app')

@section('title', 'Pembayaran - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/customer-payments.css') }}"
    >
@endpush

@section('content')

<section class="payment-page">

    <div class="container">

        <a
            href="{{ route('customer.bookings.show', $booking) }}"
            class="payment-back"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali ke Detail Booking
        </a>


        <div class="payment-heading">

            <span class="payment-kicker">
                Pembayaran
            </span>

            <h1>Konfirmasi Pembayaran</h1>

            <p>
                Lengkapi informasi pembayaran untuk booking
                {{ $booking->booking_code }}.
            </p>

        </div>


        <div class="payment-grid">

            {{-- FORM --}}
            <div class="payment-card">

                <div class="payment-card-header">

                    <div>
                        <span>Form Pembayaran</span>
                        <h2>Upload Bukti Pembayaran</h2>
                    </div>

                    <i class="bi bi-credit-card"></i>

                </div>


                @if ($errors->any())

                    <div class="payment-alert payment-alert-error">

                        <strong>
                            Data pembayaran belum lengkap.
                        </strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                @endif


                <form
                    action="{{ route('customer.payments.store', $booking) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    <div class="payment-form-group">

                        <label for="payment_method">
                            Metode Pembayaran
                        </label>

                        <select
                            name="payment_method"
                            id="payment_method"
                            required
                        >

                            <option value="">
                                Pilih metode pembayaran
                            </option>

                            <option
                                value="bank_transfer"
                                @selected(
                                    old('payment_method') === 'bank_transfer'
                                )
                            >
                                Bank Transfer
                            </option>

                            <option
                                value="e_wallet"
                                @selected(
                                    old('payment_method') === 'e_wallet'
                                )
                            >
                                E-Wallet
                            </option>

                        </select>

                    </div>


                    <div class="payment-form-group">

                        <label for="proof_of_payment">
                            Bukti Pembayaran
                        </label>

                        <input
                            type="file"
                            name="proof_of_payment"
                            id="proof_of_payment"
                            accept=".jpg,.jpeg,.png,.webp"
                            required
                        >

                        <small>
                            Format JPG, PNG, atau WebP.
                            Maksimal 5 MB.
                        </small>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary payment-submit"
                    >
                        <i class="bi bi-cloud-arrow-up"></i>
                        Kirim Bukti Pembayaran
                    </button>

                </form>

            </div>


            {{-- RINGKASAN --}}
            <aside class="payment-summary">

                <div class="payment-card">

                    <div class="payment-card-header">

                        <div>
                            <span>Ringkasan</span>
                            <h2>Detail Pembayaran</h2>
                        </div>

                        <i class="bi bi-receipt"></i>

                    </div>


                    <div class="payment-summary-row">
                        <span>Kode Booking</span>

                        <strong>
                            {{ $booking->booking_code }}
                        </strong>
                    </div>


                    <div class="payment-summary-row">
                        <span>Paket</span>

                        <strong>
                            {{ $booking->package->name ?? '-' }}
                        </strong>
                    </div>


                    <div class="payment-summary-row payment-summary-total">

                        <span>Total Pembayaran</span>

                        <strong>
                            Rp {{ number_format(
                                $booking->total_price,
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>

                    </div>


                    <div class="payment-info">

                        <i class="bi bi-info-circle"></i>

                        <p>
                            Setelah bukti pembayaran dikirim,
                            status pembayaran akan menjadi
                            <strong>Pending</strong>
                            sampai diverifikasi oleh admin.
                        </p>

                    </div>

                </div>

            </aside>

        </div>

    </div>

</section>

@endsection