<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
                'user',
                'package.service',
                'payment',
                'schedules.branch',
                'schedules.service',
            ])
            ->latest()
            ->get();

        return view(
            'admin.bookings.index',
            compact('bookings')
        );
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'package.service',
            'payment',
            'schedules.branch',
            'schedules.service',
            'schedules.photographer',
            'schedules.studioRoom',
        ]);

        return view(
            'admin.bookings.show',
            compact('booking')
        );
    }
}