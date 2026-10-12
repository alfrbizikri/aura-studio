<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Laporan Aura Studio</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            font-family: DejaVu Sans, sans-serif;
            color: #332c28;
            font-size: 10px;
        }

        .header {
            padding-bottom: 15px;
            margin-bottom: 18px;
            border-bottom: 2px solid #722f37;
        }

        .brand {
            font-size: 23px;
            font-weight: bold;
            color: #722f37;
        }

        .subtitle {
            margin-top: 3px;
            color: #8a7d75;
        }

        .report-title {
            margin-top: 18px;
            font-size: 17px;
            font-weight: bold;
        }

        .meta-table {
            width: 100%;
            margin-top: 10px;
        }

        .meta-table td {
            padding: 3px 0;
        }

        .meta-label {
            width: 100px;
            color: #83766f;
        }

        .summary-table {
            width: 100%;
            margin: 18px 0;
            border-spacing: 8px;
        }

        .summary-table td {
            width: 16.66%;
            padding: 12px 10px;
            border: 1px solid #e0d8d2;
            background: #faf8f6;
            vertical-align: top;
        }

        .summary-label {
            display: block;
            margin-bottom: 5px;
            font-size: 8px;
            color: #897c75;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 15px;
            font-weight: bold;
            color: #392f2b;
        }

        .revenue {
            background: #722f37 !important;
            color: #ffffff;
            border-color: #722f37 !important;
        }

        .revenue .summary-label,
        .revenue .summary-value {
            color: #ffffff;
        }

        .section-title {
            margin: 20px 0 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 7px 6px;
            background: #722f37;
            color: #ffffff;
            font-size: 8px;
            text-align: left;
        }

        .data-table td {
            padding: 7px 6px;
            border-bottom: 1px solid #e7dfda;
            font-size: 8px;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background: #faf8f6;
        }

        .empty {
            padding: 20px !important;
            text-align: center;
            color: #8d817a;
        }

        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #e1d9d4;
            color: #8d817a;
            font-size: 8px;
        }

        .footer-right {
            float: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="brand">AURA STUDIO</div>

        <div class="subtitle">
            Photography Studio & Creative Services
        </div>

        <div class="report-title">
            Laporan Booking & Pendapatan
        </div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Periode</td>
                <td>: {{ $periodLabel }}</td>
            </tr>

            <tr>
                <td class="meta-label">Cabang</td>
                <td>
                    :
                    {{ $selectedBranch?->name ?? 'Semua Cabang' }}
                </td>
            </tr>

            <tr>
                <td class="meta-label">Dicetak</td>
                <td>
                    :
                    {{ now()->translatedFormat('d F Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label">
                    Total Booking
                </span>

                <span class="summary-value">
                    {{ number_format($totalBooking) }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    Selesai
                </span>

                <span class="summary-value">
                    {{ number_format($completedBooking) }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    Pending
                </span>

                <span class="summary-value">
                    {{ number_format($pendingBooking) }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    Confirmed
                </span>

                <span class="summary-value">
                    {{ number_format($confirmedBooking) }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    Dibatalkan
                </span>

                <span class="summary-value">
                    {{ number_format($cancelledBooking) }}
                </span>
            </td>

            <td class="revenue">
                <span class="summary-label">
                    Pendapatan
                </span>

                <span class="summary-value">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </span>
            </td>
        </tr>
    </table>

    <div class="section-title">
        Detail Booking
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Kode</th>
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
                @endphp

                <tr>
                    <td>
                        {{ $booking->booking_code }}
                    </td>

                    <td>
                        {{ $booking->user?->name ?? '-' }}
                    </td>

                    <td>
                        @if ($schedule)
                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d/m/Y') }}
                            <br>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
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
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ ucfirst($booking->status) }}
                    </td>

                    <td>
                        @if ($payment)
                            {{ ucfirst($payment->status) }}
                        @else
                            Belum Bayar
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td
                        colspan="8"
                        class="empty"
                    >
                        Tidak ada data booking pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>
            Aura Studio — Laporan Sistem Reservasi
        </span>

        <span class="footer-right">
            Dicetak {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>

</body>

</html>