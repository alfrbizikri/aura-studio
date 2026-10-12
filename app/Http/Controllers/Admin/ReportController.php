<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);

        return view('admin.reports.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'landscape');

        $fileName = 'laporan-aura-studio-' . now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($fileName);
    }

    private function getReportData(Request $request): array
    {
        $type = $request->get('type', 'monthly');

        $month = (int) $request->get('month', now()->month);

        $year = (int) $request->get('year', now()->year);

        $branchId = $request->get('branch_id');

        $branches = Branch::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $query = Booking::query()
            ->with([
                'user',
                'package.service',
                'payment',
                'schedules.branch',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Filter Periode
        |--------------------------------------------------------------------------
        */

        if ($type === 'yearly') {
            $query->whereHas('schedules', function ($scheduleQuery) use ($year) {
                $scheduleQuery->whereYear('schedule_date', $year);
            });
        } else {
            $query->whereHas('schedules', function ($scheduleQuery) use ($month, $year) {
                $scheduleQuery
                    ->whereMonth('schedule_date', $month)
                    ->whereYear('schedule_date', $year);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Cabang
        |--------------------------------------------------------------------------
        */

        if ($branchId) {
            $query->whereHas('schedules', function ($scheduleQuery) use ($branchId) {
                $scheduleQuery->where('branch_id', $branchId);
            });
        }

        $bookings = $query
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistik Booking
        |--------------------------------------------------------------------------
        */

        $totalBooking = $bookings->count();

        $completedBooking = $bookings
            ->where('status', 'completed')
            ->count();

        $pendingBooking = $bookings
            ->where('status', 'pending')
            ->count();

        $confirmedBooking = $bookings
            ->where('status', 'confirmed')
            ->count();

        $cancelledBooking = $bookings
            ->where('status', 'cancelled')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Pendapatan
        |--------------------------------------------------------------------------
        |
        | Hanya pembayaran yang sudah berstatus paid.
        |
        */

        $totalRevenue = $bookings->sum(function ($booking) {
            if (
                $booking->payment
                && $booking->payment->status === 'paid'
            ) {
                return (float) $booking->payment->amount;
            }

            return 0;
        });

        /*
        |--------------------------------------------------------------------------
        | Label Periode
        |--------------------------------------------------------------------------
        */

        Carbon::setLocale('id');

        if ($type === 'yearly') {
            $periodLabel = 'Tahun ' . $year;
        } else {
            $periodLabel = Carbon::create($year, $month, 1)
                ->translatedFormat('F Y');
        }

        $selectedBranch = $branchId
            ? $branches->firstWhere('id', $branchId)
            : null;

        return [
            'bookings' => $bookings,
            'branches' => $branches,

            'type' => $type,
            'month' => $month,
            'year' => $year,
            'branchId' => $branchId,

            'periodLabel' => $periodLabel,
            'selectedBranch' => $selectedBranch,

            'totalBooking' => $totalBooking,
            'completedBooking' => $completedBooking,
            'pendingBooking' => $pendingBooking,
            'confirmedBooking' => $confirmedBooking,
            'cancelledBooking' => $cancelledBooking,

            'totalRevenue' => $totalRevenue,
        ];
    }
}