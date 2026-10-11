<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        abort_if(
            $booking->user_id !== Auth::id(),
            403
        );

        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('customer.bookings.show', $booking)
                ->with(
                    'error',
                    'Booking yang dibatalkan tidak dapat melakukan pembayaran.'
                );
        }

        $booking->load([
            'payment',
            'package.service',
            'schedules.branch',
            'schedules.photographer',
            'schedules.studioRoom',
        ]);

        $payment = $booking->payment;

        /*
        |--------------------------------------------------------------------------
        | Payment pending / paid / refunded
        |--------------------------------------------------------------------------
        |
        | Tidak boleh upload bukti lagi.
        |
        */
        if (
            $payment
            && in_array(
                $payment->status,
                ['pending', 'paid', 'refunded'],
                true
            )
        ) {
            return redirect()
                ->route('customer.bookings.show', $booking)
                ->with(
                    'error',
                    $payment->status === 'pending'
                        ? 'Bukti pembayaran sudah dikirim dan sedang menunggu verifikasi.'
                        : 'Pembayaran untuk booking ini sudah diproses.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Jika rejected
        |--------------------------------------------------------------------------
        |
        | Halaman payment tetap dibuka agar customer
        | dapat mengirim bukti baru.
        |
        */
        $isResubmission =
            $payment
            && $payment->status === 'rejected';

        return view(
            'customer.payments.create',
            compact(
                'booking',
                'payment',
                'isResubmission'
            )
        );
    }

    public function store(
        Request $request,
        Booking $booking
    ) {
        abort_if(
            $booking->user_id !== Auth::id(),
            403
        );

        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('customer.bookings.show', $booking)
                ->with(
                    'error',
                    'Booking yang dibatalkan tidak dapat melakukan pembayaran.'
                );
        }

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:bank_transfer,e_wallet',
            ],

            'proof_of_payment' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'payment_method.required' =>
                'Silakan pilih metode pembayaran.',

            'payment_method.in' =>
                'Metode pembayaran tidak valid.',

            'proof_of_payment.required' =>
                'Bukti pembayaran wajib diunggah.',

            'proof_of_payment.image' =>
                'Bukti pembayaran harus berupa gambar.',

            'proof_of_payment.mimes' =>
                'Format bukti pembayaran harus JPG, JPEG, PNG, atau WebP.',

            'proof_of_payment.max' =>
                'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        $payment = $booking->payment()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Payment sudah ada
        |--------------------------------------------------------------------------
        */
        if ($payment) {

            if ($payment->status !== 'rejected') {
                return redirect()
                    ->route('customer.bookings.show', $booking)
                    ->with(
                        'error',
                        'Pembayaran untuk booking ini sudah dikirim atau telah diproses.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Re-upload bukti rejected
            |--------------------------------------------------------------------------
            */
            $newProofPath = $request
                ->file('proof_of_payment')
                ->store('payments', 'public');

            $oldProofPath =
                $payment->proof_of_payment;

            $payment->update([
                'payment_method' =>
                    $validated['payment_method'],

                'amount' =>
                    $booking->total_price,

                'proof_of_payment' =>
                    $newProofPath,

                'status' =>
                    'pending',

                'rejection_reason' =>
                    null,

                'verified_by' =>
                    null,

                'verified_at' =>
                    null,
            ]);

            if (
                $oldProofPath
                && Storage::disk('public')
                    ->exists($oldProofPath)
            ) {
                Storage::disk('public')
                    ->delete($oldProofPath);
            }

            return redirect()
                ->route('customer.bookings.show', $booking)
                ->with(
                    'success',
                    'Bukti pembayaran baru berhasil dikirim dan menunggu verifikasi admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment pertama
        |--------------------------------------------------------------------------
        */
        $proofPath = $request
            ->file('proof_of_payment')
            ->store('payments', 'public');

        $booking->payment()->create([
            'payment_method' =>
                $validated['payment_method'],

            'amount' =>
                $booking->total_price,

            'proof_of_payment' =>
                $proofPath,

            'status' =>
                'pending',

            'rejection_reason' =>
                null,

            'verified_by' =>
                null,

            'verified_at' =>
                null,
        ]);

        return redirect()
            ->route('customer.bookings.show', $booking)
            ->with(
                'success',
                'Bukti pembayaran berhasil dikirim dan menunggu verifikasi admin.'
            );
    }
}