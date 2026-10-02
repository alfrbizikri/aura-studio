<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
                'package',
                'payment',
                'schedules',
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'customer.bookings.index',
            compact('bookings')
        );
    }

    public function show(Booking $booking)
    {
        abort_if(
            $booking->user_id !== Auth::id(),
            403
        );

        $booking->load([
            'package.service',
            'payment',
            'schedules.branch',
            'schedules.service',
            'schedules.photographer',
            'schedules.studioRoom',
        ]);

        return view(
            'customer.bookings.show',
            compact('booking')
        );
    }
}