@extends('layouts.admin')

@section('title', 'Kelola Jadwal - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <span class="admin-label">
            OPERASIONAL
        </span>

        <h1>
            Kelola Jadwal
        </h1>

        <p>
            Lihat seluruh jadwal layanan Aura Studio.
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
            Total Jadwal
        </span>

        <strong>
            {{ $schedules->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Tersedia
        </span>

        <strong>
            {{ $schedules->where('status', 'available')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Terbooking
        </span>

        <strong>
            {{ $schedules->where('status', 'booked')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Diblokir
        </span>

        <strong>
            {{ $schedules->where('status', 'blocked')->count() }}
        </strong>

    </div>

</div>


{{-- TABLE --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Jadwal
        </h2>

        <p>
            Data jadwal layanan yang tersimpan pada sistem.
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

                    <th>Layanan</th>

                    <th>Fotografer</th>

                    <th>Studio</th>

                    <th>Status</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($schedules as $schedule)

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

                        {{ $schedule->service?->name ?? '-' }}

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
                        colspan="8"
                        class="admin-empty"
                    >

                        Belum ada data jadwal.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection