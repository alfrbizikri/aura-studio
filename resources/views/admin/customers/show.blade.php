@extends('layouts.admin')

@section('title', 'Detail Customer - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.customers.index') }}"
            class="admin-back-link"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <span class="admin-label">
            CUSTOMER
        </span>

        <h1>
            Detail Customer
        </h1>

        <p>
            Informasi customer dan riwayat booking.
        </p>

    </div>

</div>


{{-- INFORMASI CUSTOMER --}}
<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Customer
        </h2>

        <p>
            Data akun customer yang terdaftar.
        </p>

    </div>


    <div class="admin-form-grid">

        <div class="admin-form-group">

            <label>Nama</label>

            <strong>
                {{ $user->name }}
            </strong>

        </div>


        <div class="admin-form-group">

            <label>Email</label>

            <span>
                {{ $user->email }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Nomor Telepon</label>

            <span>
                {{ $user->phone ?? '-' }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Role</label>

            <span class="admin-status active">
                Customer
            </span>

        </div>


        <div class="admin-form-group">

            <label>Tanggal Daftar</label>

            <span>

                {{ $user->created_at
                    ? $user->created_at->format('d/m/Y H:i')
                    : '-'
                }}

            </span>

        </div>


        <div class="admin-form-group">

            <label>Total Booking</label>

            <strong>
                {{ $user->bookings->count() }}
            </strong>

        </div>

    </div>

</div>


{{-- RIWAYAT BOOKING --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Riwayat Booking
        </h2>

        <p>
            Booking yang pernah dilakukan oleh customer.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Kode Booking</th>

                    <th>Paket</th>

                    <th>Jadwal</th>

                    <th>Total</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($user->bookings as $booking)

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
                                title="Lihat Booking"
                            >

                                <i class="bi bi-eye"></i>

                            </a>

                        </div>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="7"
                        class="admin-empty"
                    >

                        Customer ini belum memiliki booking.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection