@extends('layouts.admin')

@section('title', 'Daftar Booking - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <span class="admin-label">
            TRANSAKSI
        </span>

        <h1>
            Daftar Booking
        </h1>

        <p>
            Lihat dan kelola seluruh booking customer Aura Studio.
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
            Total Booking
        </span>

        <strong>
            {{ $bookings->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Pending
        </span>

        <strong>
            {{ $bookings->where('status', 'pending')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Dikonfirmasi
        </span>

        <strong>
            {{ $bookings->where('status', 'confirmed')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Selesai
        </span>

        <strong>
            {{ $bookings->where('status', 'completed')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Dibatalkan
        </span>

        <strong>
            {{ $bookings->where('status', 'cancelled')->count() }}
        </strong>

    </div>

</div>


{{-- TABLE --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Data Booking
        </h2>

        <p>
            Daftar booking yang dilakukan oleh customer.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Kode Booking</th>

                    <th>Customer</th>

                    <th>Paket</th>

                    <th>Jadwal</th>

                    <th>Total</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($bookings as $booking)

                @php
                    $schedule = $booking->schedules
                        ->sortBy('schedule_date')
                        ->first();
                @endphp

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>


                    <td>

                        <strong>
                            {{ $booking->booking_code }}
                        </strong>

                    </td>


                    <td>

                        {{ $booking->user?->name ?? '-' }}

                    </td>


                    <td>

                        {{ $booking->package?->name ?? '-' }}

                    </td>


                    <td>

                        @if ($schedule)

                            {{ $schedule->schedule_date->format('d/m/Y') }}

                            <br>

                            <small>

                                {{ \Carbon\Carbon::parse(
                                    $schedule->start_time
                                )->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse(
                                    $schedule->end_time
                                )->format('H:i') }}

                            </small>

                        @else

                            -

                        @endif

                    </td>


                    <td>

                        Rp {{ number_format(
                            $booking->total_price,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>


                    <td>

                        @if ($booking->status === 'pending')

                            <span class="admin-status">
                                Pending
                            </span>

                        @elseif ($booking->status === 'confirmed')

                            <span class="admin-status active">
                                Dikonfirmasi
                            </span>

                        @elseif ($booking->status === 'completed')

                            <span class="admin-status active">
                                Selesai
                            </span>

                        @else

                            <span class="admin-status inactive">
                                Dibatalkan
                            </span>

                        @endif

                    </td>


                    <td>

                        <div class="admin-actions">

                            <a
                                href="{{ route(
                                    'admin.bookings.show',
                                    $booking->id
                                ) }}"
                                class="admin-action-btn"
                                title="Lihat Detail"
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

                        Belum ada data booking.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection