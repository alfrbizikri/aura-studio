@extends('layouts.admin')

@section('title', 'Detail Booking - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.bookings.index') }}"
            class="admin-back-link"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <span class="admin-label">
            TRANSAKSI
        </span>

        <h1>
            Detail Booking
        </h1>

        <p>
            Informasi lengkap booking
            <strong>{{ $booking->booking_code }}</strong>.
        </p>

    </div>

</div>


{{-- STATUS BOOKING --}}
<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Booking
        </h2>

        <p>
            Data utama pesanan customer.
        </p>

    </div>


    <div class="admin-form-grid">

        <div class="admin-form-group">

            <label>Kode Booking</label>

            <strong>
                {{ $booking->booking_code }}
            </strong>

        </div>


        <div class="admin-form-group">

            <label>Status Booking</label>

            <div>

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

            </div>

        </div>


        <div class="admin-form-group">

            <label>Customer</label>

            <strong>
                {{ $booking->user?->name ?? '-' }}
            </strong>

        </div>


        <div class="admin-form-group">

            <label>Email Customer</label>

            <span>
                {{ $booking->user?->email ?? '-' }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Jumlah Orang</label>

            <span>
                {{ $booking->number_of_people }} orang
            </span>

        </div>


        <div class="admin-form-group">

            <label>Total Harga</label>

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

</div>



{{-- PAKET --}}
<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Paket & Layanan
        </h2>

        <p>
            Paket yang dipilih oleh customer.
        </p>

    </div>


    <div class="admin-form-grid">

        <div class="admin-form-group">

            <label>Paket</label>

            <strong>
                {{ $booking->package?->name ?? '-' }}
            </strong>

        </div>


        <div class="admin-form-group">

            <label>Layanan</label>

            <span>
                {{ $booking->package?->service?->name ?? '-' }}
            </span>

        </div>


        <div class="admin-form-group">

            <label>Harga Paket</label>

            <span>

                @if ($booking->package)

                    Rp {{ number_format(
                        $booking->package->price,
                        0,
                        ',',
                        '.'
                    ) }}

                @else

                    -

                @endif

            </span>

        </div>

    </div>

</div>



{{-- JADWAL --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Jadwal Booking
        </h2>

        <p>
            Jadwal yang digunakan pada booking ini.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Tanggal</th>

                    <th>Waktu</th>

                    <th>Cabang</th>

                    <th>Fotografer</th>

                    <th>Studio</th>

                    <th>Status</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($booking->schedules as $schedule)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>


                    <td>

                        {{ $schedule->schedule_date->format('d/m/Y') }}

                    </td>


                    <td>

                        {{ \Carbon\Carbon::parse(
                            $schedule->start_time
                        )->format('H:i') }}

                        -

                        {{ \Carbon\Carbon::parse(
                            $schedule->end_time
                        )->format('H:i') }}

                    </td>


                    <td>

                        {{ $schedule->branch?->name ?? '-' }}

                    </td>


                    <td>

                        {{ $schedule->photographer?->name ?? '-' }}

                    </td>


                    <td>

                        {{ $schedule->studioRoom?->name ?? '-' }}

                    </td>


                    <td>

                        @if ($schedule->status === 'available')

                            <span class="admin-status active">
                                Tersedia
                            </span>

                        @elseif ($schedule->status === 'booked')

                            <span class="admin-status">
                                Terbooking
                            </span>

                        @else

                            <span class="admin-status inactive">
                                Diblokir
                            </span>

                        @endif

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="7"
                        class="admin-empty"
                    >
                        Belum ada jadwal yang terhubung.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- LOKASI --}}
@if (
    $booking->location_address
    || $booking->location_notes
)

    <div class="admin-form-card">

        <div class="admin-form-title">

            <h2>
                Informasi Lokasi
            </h2>

            <p>
                Detail lokasi untuk layanan di luar studio.
            </p>

        </div>


        <div class="admin-form-grid">

            <div class="admin-form-group full">

                <label>Alamat Lokasi</label>

                <span>
                    {{ $booking->location_address ?? '-' }}
                </span>

            </div>


            <div class="admin-form-group full">

                <label>Catatan Lokasi</label>

                <span>
                    {{ $booking->location_notes ?? '-' }}
                </span>

            </div>

        </div>

    </div>

@endif



{{-- CATATAN --}}
@if ($booking->notes)

    <div class="admin-form-card">

        <div class="admin-form-title">

            <h2>
                Catatan Customer
            </h2>

        </div>


        <div class="admin-form-group full">

            <span>
                {{ $booking->notes }}
            </span>

        </div>

    </div>

@endif



{{-- PEMBAYARAN --}}
<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Pembayaran
        </h2>

        <p>
            Informasi pembayaran booking.
        </p>

    </div>


    @if ($booking->payment)

        <div class="admin-form-grid">

            <div class="admin-form-group">

                <label>Status Pembayaran</label>

                <span>
                    {{ ucfirst($booking->payment->status) }}
                </span>

            </div>


            <div class="admin-form-group">

                <label>Nominal</label>

                <strong>
                    Rp {{ number_format(
                        $booking->payment->amount,
                        0,
                        ',',
                        '.'
                    ) }}
                </strong>

            </div>

        </div>

    @else

        <div class="admin-empty">

            Belum ada pembayaran untuk booking ini.

        </div>

    @endif

</div>

@endsection