<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        // Customer hanya boleh membayar booking miliknya sendiri
        abort_if(
            $booking->user_id !== Auth::id(),
            403
        );

        // Ambil payment jika sudah ada
        $booking->load('payment', 'package');

        // Kalau pembayaran sudah pernah dikirim,
        // kembali ke detail booking
        if ($booking->payment) {
            return redirect()
                ->route('customer.bookings.show', $booking)
                ->with('error', 'Pembayaran untuk booking ini sudah dikirim.');
        }

        return view(
            'customer.payments.create',
            compact('booking')
        );
    }

    public function store(Request $request, Booking $booking)
    {
        // Pastikan booking memang milik customer login
        abort_if(
            $booking->user_id !== Auth::id(),
            403
        );

        // Cegah pembayaran ganda
        if ($booking->payment()->exists()) {
            return redirect()
                ->route('customer.bookings.show', $booking)
                ->with('error', 'Pembayaran untuk booking ini sudah ada.');
        }

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:bank_transfer,e_wallet'
            ],

            'proof_of_payment' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],
        ]);

        $proofPath = $request
            ->file('proof_of_payment')
            ->store('payments', 'public');

        $booking->payment()->create([
            'payment_method' => $validated['payment_method'],
            'amount' => $booking->total_price,
            'proof_of_payment' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('customer.bookings.show', $booking)
            ->with(
                'success',
                'Bukti pembayaran berhasil dikirim dan menunggu verifikasi.'
            );
    }
}
