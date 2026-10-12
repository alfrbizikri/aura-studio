<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Cabang aktif
        |--------------------------------------------------------------------------
        */
        $branches = Branch::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Cabang terpilih
        |--------------------------------------------------------------------------
        |
        | Kalau belum ada query ?branch=,
        | otomatis gunakan cabang aktif pertama.
        |
        */
        $requestedBranchId = (int) $request->input(
            'branch',
            $branches->first()?->id
        );

        $selectedBranch = $branches->firstWhere(
            'id',
            $requestedBranchId
        );

        if (!$selectedBranch) {
            $selectedBranch = $branches->first();
        }

        abort_unless(
            $selectedBranch,
            404,
            'Belum ada cabang aktif.'
        );

        /*
        |--------------------------------------------------------------------------
        | Layanan pada cabang terpilih
        |--------------------------------------------------------------------------
        */
        $services = $selectedBranch
            ->services()
            ->where('services.status', 'active')
            ->wherePivot('status', 'active')
            ->orderBy('services.name')
            ->get();

        $selectedServiceId = $request->filled('service')
            ? (int) $request->input('service')
            : null;

        /*
         * Jangan menerima service yang sebenarnya
         * tidak tersedia pada cabang tersebut.
         */
        if (
            $selectedServiceId
            && !$services->contains('id', $selectedServiceId)
        ) {
            $selectedServiceId = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Minggu terpilih
        |--------------------------------------------------------------------------
        */
        try {

            $weekStart = $request->filled('week')
                ? Carbon::parse($request->input('week'))
                    ->startOfWeek(Carbon::MONDAY)
                : today()->startOfWeek(Carbon::MONDAY);

        } catch (\Throwable $exception) {

            $weekStart = today()
                ->startOfWeek(Carbon::MONDAY);
        }

        $weekEnd = $weekStart
            ->copy()
            ->endOfWeek(Carbon::SUNDAY);

        $previousWeek = $weekStart
            ->copy()
            ->subWeek()
            ->toDateString();

        $nextWeek = $weekStart
            ->copy()
            ->addWeek()
            ->toDateString();

        $currentWeek = today()
            ->startOfWeek(Carbon::MONDAY)
            ->toDateString();

        /*
        |--------------------------------------------------------------------------
        | 7 hari pada kalender
        |--------------------------------------------------------------------------
        */
        $weekDays = collect();

        for ($day = 0; $day < 7; $day++) {

            $date = $weekStart
                ->copy()
                ->addDays($day);

            $weekDays->push($date);
        }

        /*
        |--------------------------------------------------------------------------
        | Jam operasional cabang
        |--------------------------------------------------------------------------
        */
        $openingTime = Carbon::parse(
            $selectedBranch->opening_time
        );

        $closingTime = Carbon::parse(
            $selectedBranch->closing_time
        );

        $openingMinutes =
            ((int) $openingTime->format('H') * 60)
            + (int) $openingTime->format('i');

        $closingMinutes =
            ((int) $closingTime->format('H') * 60)
            + (int) $closingTime->format('i');

        /*
        |--------------------------------------------------------------------------
        | Slot waktu
        |--------------------------------------------------------------------------
        |
        | Kita gunakan interval 30 menit.
        |
        | Contoh:
        | 09:00
        | 09:30
        | 10:00
        | 10:30
        | ...
        |
        */
        $slotMinutes = 30;

        $timeSlots = collect();

        $cursor = $openingTime->copy();

        while ($cursor->lt($closingTime)) {

            $timeSlots->push(
                $cursor->format('H:i')
            );

            $cursor->addMinutes(
                $slotMinutes
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Jadwal pada minggu terpilih
        |--------------------------------------------------------------------------
        */
        $scheduleQuery = Schedule::query()
            ->with([
                'branch',
                'service',
                'photographer',
                'studioRoom',
            ])
            ->where(
                'branch_id',
                $selectedBranch->id
            )
            ->whereBetween(
                'schedule_date',
                [
                    $weekStart->toDateString(),
                    $weekEnd->toDateString(),
                ]
            )
            ->whereIn('status', ['booked', 'blocked']);

        if ($selectedServiceId) {

            $scheduleQuery->where(
                'service_id',
                $selectedServiceId
            );
        }

        $schedules = $scheduleQuery
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Informasi tambahan untuk Schedule Board
        |--------------------------------------------------------------------------
        */
        $schedules->each(function ($schedule) use (
            $weekStart,
            $openingMinutes,
            $closingMinutes,
            $slotMinutes
        ) {

            $scheduleDate = $schedule
                ->schedule_date
                ->format('Y-m-d');

            $startDateTime = Carbon::parse(
                $scheduleDate
                . ' '
                . $schedule->start_time
            );

            $endDateTime = Carbon::parse(
                $scheduleDate
                . ' '
                . $schedule->end_time
            );

            $startClock = Carbon::parse(
                $schedule->start_time
            );

            $endClock = Carbon::parse(
                $schedule->end_time
            );

            $startMinutes =
                ((int) $startClock->format('H') * 60)
                + (int) $startClock->format('i');

            $endMinutes =
                ((int) $endClock->format('H') * 60)
                + (int) $endClock->format('i');

            /*
             * Apakah jadwal sudah lewat?
             */
            $schedule->is_past =
                $endDateTime->lte(now());

            /*
             * Validasi terhadap jam buka/tutup cabang.
             */
            $schedule->outside_operating_hours =
                $startMinutes < $openingMinutes
                || $endMinutes > $closingMinutes;

            /*
             * Status khusus tampilan admin.
             *
             * Tidak mengubah status di database.
             */
            if ($schedule->outside_operating_hours) {

                $schedule->display_status =
                    'invalid';

            } elseif ($schedule->is_past) {

                $schedule->display_status =
                    'past';

            } else {

                $schedule->display_status =
                    $schedule->status;
            }

            /*
             * Posisi hari pada grid.
             *
             * Senin = kolom pertama
             * Minggu = kolom ketujuh
             */
            $schedule->board_day =
                (int) $weekStart->diffInDays(
                    $schedule->schedule_date
                );

            /*
             * Jadwal yang berada di luar jam operasional
             * tidak ditempatkan pada grid utama.
             */
            if ($schedule->outside_operating_hours) {
                return;
            }

            /*
             * Posisi awal pada timeline.
             */
            $minutesFromOpening =
                $startMinutes - $openingMinutes;

            $schedule->board_slot =
                intdiv(
                    $minutesFromOpening,
                    $slotMinutes
                );

            /*
             * Berapa slot yang digunakan.
             *
             * Contoh:
             * 10:00 - 11:30
             * = 90 menit
             * = 3 slot @ 30 menit
             */
            $durationMinutes =
                max(
                    $slotMinutes,
                    $endMinutes - $startMinutes
                );

            $schedule->board_span =
                max(
                    1,
                    (int) ceil(
                        $durationMinutes
                        / $slotMinutes
                    )
                );
        });

        /*
        |--------------------------------------------------------------------------
        | Schedule valid untuk kalender
        |--------------------------------------------------------------------------
        */
        $boardSchedules = $schedules
            ->reject(
                fn ($schedule) =>
                    $schedule->outside_operating_hours
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Schedule yang bermasalah
        |--------------------------------------------------------------------------
        |
        | Ini tidak kita hilangkan.
        | Admin tetap diberi tahu supaya bisa dibenahi.
        |
        */
        $invalidSchedules = $schedules
            ->filter(
                fn ($schedule) =>
                    $schedule->outside_operating_hours
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Statistik periode
        |--------------------------------------------------------------------------
        |
        | available yang sudah lewat TIDAK dihitung
        | lagi sebagai "Tersedia".
        |
        */
        $stats = [
            'total' => $schedules->count(),

            'booked' => $schedules
                ->where(
                    'display_status',
                    'booked'
                )
                ->count(),

            'blocked' => $schedules
                ->where(
                    'display_status',
                    'blocked'
                )
                ->count(),

            'past' => $schedules
                ->where(
                    'display_status',
                    'past'
                )
                ->count(),

            'invalid' => $invalidSchedules
                ->count(),
        ];

        return view(
            'admin.schedules.index',
            compact(
                'branches',
                'selectedBranch',
                'services',
                'selectedServiceId',
                'weekStart',
                'weekEnd',
                'previousWeek',
                'nextWeek',
                'currentWeek',
                'weekDays',
                'openingTime',
                'closingTime',
                'slotMinutes',
                'timeSlots',
                'schedules',
                'boardSchedules',
                'invalidSchedules',
                'stats'
            )
        );
    }
}