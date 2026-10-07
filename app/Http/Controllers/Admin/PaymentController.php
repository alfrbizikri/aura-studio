<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
                'booking.user',
                'booking.package',
                'verifier',
            ])
            ->latest()
            ->get();

        return view(
            'admin.payments.index',
            compact('payments')
        );
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'booking.user',
            'booking.package.service',
            'booking.schedules.branch',
            'booking.schedules.photographer',
            'booking.schedules.studioRoom',
            'verifier',
        ]);

        return view(
            'admin.payments.show',
            compact('payment')
        );
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()
                ->route('admin.payments.show', $payment)
                ->with(
                    'error',
                    'Pembayaran ini sudah diproses sebelumnya.'
                );
        }

        $payment->update([
            'status' => 'paid',
            'rejection_reason' => null,
            'verified_at' => now(),
        ]);

        $payment->booking()->update([
            'status' => 'confirmed',
        ]);

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with(
                'success',
                'Pembayaran berhasil diverifikasi.'
            );
    }

    public function reject(
        Request $request,
        Payment $payment
    ) {
        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if ($payment->status !== 'pending') {
            return redirect()
                ->route('admin.payments.show', $payment)
                ->with(
                    'error',
                    'Pembayaran ini sudah diproses sebelumnya.'
                );
        }

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with(
                'success',
                'Pembayaran telah ditolak.'
            );
    }
}