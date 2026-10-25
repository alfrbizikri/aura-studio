<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

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

    public function complete(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with(
                'error',
                'Hanya booking yang telah dikonfirmasi yang dapat ditandai selesai.'
            );
        }

        $booking->update([
            'status' => 'completed',
        ]);

        return back()->with(
            'success',
            'Booking berhasil ditandai selesai. Pelanggan sekarang dapat memberikan rating.'
        );
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking ini sudah dibatalkan.');
        }

        if ($booking->status === 'completed') {
            return back()->with('error', 'Booking yang sudah selesai tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($booking) {

            // Ubah status booking
            $booking->update([
                'status' => 'cancelled',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Bebaskan Jadwal
            |--------------------------------------------------------------------------
            |
            | Schedule berstatus "booked" harus dibebaskan agar slot tersebut
            | dapat dipilih kembali oleh customer.
            |
            */

            foreach ($booking->schedules as $schedule) {
                if ($schedule->status === 'booked') {
                    $schedule->update([
                        'status' => 'available',
                    ]);
                }
            }
        });

        return back()->with(
            'success',
            'Booking berhasil dibatalkan dan jadwal telah tersedia kembali.'
        );
    }
}