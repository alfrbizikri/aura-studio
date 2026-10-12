@extends('layouts.admin')

@section('title', 'Laporan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-report.css') }}">
@endpush

@section('content')
    <div class="report-page">

        {{-- HEADER --}}
        <div class="report-header">
            <div>
                <span class="report-eyebrow">Analitik & Rekapitulasi</span>
                <h1>Laporan</h1>
                <p>
                    Pantau performa booking dan pendapatan Aura Studio
                    berdasarkan periode dan cabang.
                </p>
            </div>

            <a
                href="{{ route('admin.reports.pdf', [
                    'type' => $type,
                    'month' => $month,
                    'year' => $year,
                    'branch_id' => $branchId,
                ]) }}"
                class="report-export"
            >
                <i class="bi bi-file-earmark-pdf"></i>
                Export PDF
            </a>
        </div>

        {{-- FILTER --}}
        <div class="report-filter-card">
            <div class="filter-heading">
                <div class="filter-icon">
                    <i class="bi bi-funnel"></i>
                </div>

                <div>
                    <h2>Filter Laporan</h2>
                    <p>Tentukan periode laporan yang ingin ditampilkan.</p>
                </div>
            </div>

            <form
                action="{{ route('admin.reports.index') }}"
                method="GET"
                class="report-filter-form"
            >
                <div class="form-group">
                    <label for="type">Periode</label>

                    <select
                        name="type"
                        id="type"
                        class="form-control"
                    >
                        <option
                            value="monthly"
                            {{ $type === 'monthly' ? 'selected' : '' }}
                        >
                            Bulanan
                        </option>

                        <option
                            value="yearly"
                            {{ $type === 'yearly' ? 'selected' : '' }}
                        >
                            Tahunan
                        </option>
                    </select>
                </div>

                <div
                    class="form-group"
                    id="month-filter"
                >
                    <label for="month">Bulan</label>

                    <select
                        name="month"
                        id="month"
                        class="form-control"
                    >
                        @php
                            $months = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp

                        @foreach ($months as $number => $monthName)
                            <option
                                value="{{ $number }}"
                                {{ (int) $month === $number ? 'selected' : '' }}
                            >
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="year">Tahun</label>

                    <select
                        name="year"
                        id="year"
                        class="form-control"
                    >
                        @for ($reportYear = now()->year; $reportYear >= now()->year - 5; $reportYear--)
                            <option
                                value="{{ $reportYear }}"
                                {{ (int) $year === $reportYear ? 'selected' : '' }}
                            >
                                {{ $reportYear }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="branch_id">Cabang</label>

                    <select
                        name="branch_id"
                        id="branch_id"
                        class="form-control"
                    >
                        <option value="">Semua Cabang</option>

                        @foreach ($branches as $branch)
                            <option
                                value="{{ $branch->id }}"
                                {{ (string) $branchId === (string) $branch->id ? 'selected' : '' }}
                            >
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-action">
                    <button
                        type="submit"
                        class="btn-filter"
                    >
                        <i class="bi bi-search"></i>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- PERIOD INFO --}}
        <div class="report-period">
            <div>
                <span>Periode Laporan</span>
                <strong>{{ $periodLabel }}</strong>
            </div>

            <div>
                <span>Cabang</span>
                <strong>
                    {{ $selectedBranch?->name ?? 'Semua Cabang' }}
                </strong>
            </div>
        </div>

        {{-- STATISTICS --}}
        <div class="report-stats">

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div>
                    <span>Total Booking</span>
                    <strong>{{ number_format($totalBooking) }}</strong>
                    <small>Booking pada periode ini</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <span>Selesai</span>
                    <strong>{{ number_format($completedBooking) }}</strong>
                    <small>Booking telah selesai</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <span>Pending</span>
                    <strong>{{ number_format($pendingBooking) }}</strong>
                    <small>Menunggu proses</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-patch-check"></i>
                </div>

                <div>
                    <span>Confirmed</span>
                    <strong>{{ number_format($confirmedBooking) }}</strong>
                    <small>Booking terkonfirmasi</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div>
                    <span>Dibatalkan</span>
                    <strong>{{ number_format($cancelledBooking) }}</strong>
                    <small>Booking dibatalkan</small>
                </div>
            </div>

            <div class="stat-card revenue-card">
                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <div>
                    <span>Total Pendapatan</span>
                    <strong>
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </strong>
                    <small>Dari pembayaran yang telah lunas</small>
                </div>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="report-table-card">
            <div class="table-heading">
                <div>
                    <span class="report-eyebrow">Detail Transaksi</span>
                    <h2>Daftar Booking</h2>
                </div>

                <span class="table-count">
                    {{ $bookings->count() }} data
                </span>
            </div>

            <div class="report-table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Layanan</th>
                            <th>Cabang</th>
                            <th>Total</th>
                            <th>Booking</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bookings as $booking)
                            @php
                                $schedule = $booking->schedules->first();
                                $payment = $booking->payment;

                                $bookingStatusClass = match ($booking->status) {
                                    'completed' => 'completed',
                                    'confirmed' => 'confirmed',
                                    'cancelled' => 'cancelled',
                                    default => 'pending',
                                };

                                $bookingStatusLabel = match ($booking->status) {
                                    'completed' => 'Selesai',
                                    'confirmed' => 'Confirmed',
                                    'cancelled' => 'Dibatalkan',
                                    default => 'Pending',
                                };

                                $paymentStatusClass = match ($payment?->status) {
                                    'paid' => 'paid',
                                    'rejected' => 'rejected',
                                    'refunded' => 'refunded',
                                    default => 'pending',
                                };

                                $paymentStatusLabel = match ($payment?->status) {
                                    'paid' => 'Lunas',
                                    'rejected' => 'Ditolak',
                                    'refunded' => 'Refund',
                                    default => $payment ? 'Pending' : 'Belum Bayar',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <span class="booking-code">
                                        {{ $booking->booking_code }}
                                    </span>
                                </td>

                                <td>
                                    <div class="customer-cell">
                                        <strong>
                                            {{ $booking->user?->name ?? '-' }}
                                        </strong>

                                        <small>
                                            {{ $booking->user?->email ?? '-' }}
                                        </small>
                                    </div>
                                </td>

                                <td>
                                    @if ($schedule)
                                        <div class="date-cell">
                                            <strong>
                                                {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d M Y') }}
                                            </strong>

                                            <small>
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </small>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $booking->package?->service?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $schedule?->branch?->name ?? '-' }}
                                </td>

                                <td>
                                    <strong>
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="status-badge {{ $bookingStatusClass }}">
                                        {{ $bookingStatusLabel }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge {{ $paymentStatusClass }}">
                                        {{ $paymentStatusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="8"
                                    class="empty-state"
                                >
                                    <div class="empty-icon">
                                        <i class="bi bi-file-earmark-bar-graph"></i>
                                    </div>

                                    <strong>Belum ada data laporan</strong>

                                    <p>
                                        Tidak ada booking yang ditemukan
                                        pada filter periode ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('type');
            const monthFilter = document.getElementById('month-filter');

            function toggleMonthFilter() {
                if (typeSelect.value === 'yearly') {
                    monthFilter.style.display = 'none';
                } else {
                    monthFilter.style.display = 'flex';
                }
            }

            typeSelect.addEventListener('change', toggleMonthFilter);

            toggleMonthFilter();
        });
    </script>
@endsection