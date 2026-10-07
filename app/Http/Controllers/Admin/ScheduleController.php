<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with([
                'branch',
                'service',
                'photographer',
                'studioRoom',
            ])
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        return view(
            'admin.schedules.index',
            compact('schedules')
        );
    }
}