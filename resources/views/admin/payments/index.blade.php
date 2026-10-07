@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <span class="admin-label">
            TRANSAKSI
        </span>

        <h1>
            Verifikasi Pembayaran
        </h1>

        <p>
            Periksa dan verifikasi pembayaran booking customer.
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


{{-- STATISTIK --}}
<div class="admin-stats">

    <div class="admin-stat-card">

        <span>
            Total Pembayaran
        </span>

        <strong>
            {{ $payments->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Menunggu Verifikasi
        </span>

        <strong>
            {{ $payments->where('status', 'pending')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Dibayar
        </span>

        <strong>
            {{ $payments->where('status', 'paid')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Ditolak
        </span>

        <strong>
            {{ $payments->where('status', 'rejected')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Refund
        </span>

        <strong>
            {{ $payments->where('status', 'refunded')->count() }}
        </strong>

    </div>

</div>


{{-- TABLE --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Pembayaran
        </h2>

        <p>
            Data pembayaran customer yang masuk ke sistem.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Kode Booking</th>

                    <th>Customer</th>

                    <th>Metode</th>

                    <th>Nominal</th>

                    <th>Status</th>

                    <th>Tanggal</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($payments as $payment)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>


                    <td>

                        <strong>
                            {{ $payment->booking?->booking_code ?? '-' }}
                        </strong>

                    </td>


                    <td>

                        {{ $payment->booking?->user?->name ?? '-' }}

                    </td>


                    <td>

                        {{ $payment->payment_method }}

                    </td>


                    <td>

                        Rp {{ number_format(
                            $payment->amount,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>


                    <td>

                        @if ($payment->status === 'pending')

                            <span class="admin-status">
                                Menunggu
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

                    </td>


                    <td>

                        {{ $payment->created_at?->format('d/m/Y H:i') ?? '-' }}

                    </td>


                    <td>

                        <div class="admin-actions">

                            <a
                                href="{{ route(
                                    'admin.payments.show',
                                    $payment->id
                                ) }}"
                                class="admin-action-btn"
                                title="Periksa Pembayaran"
                            >

                                <i class="bi bi-eye"></i>

                            </a>

                        </div>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="8"
                        class="admin-empty"
                    >

                        Belum ada data pembayaran.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection