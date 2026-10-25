<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * Menampilkan semua ulasan dan menyediakan filter status.
     */
    public function index(Request $request): View
    {
        $allowedStatuses = [
            'all',
            'pending',
            'published',
            'hidden',
        ];

        $status = $request->query('status', 'all');

        if (! in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $query = Testimonial::with([
            'booking.user',
            'booking.package',
        ])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $testimonials = $query
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all' => Testimonial::count(),
            'pending' => Testimonial::where('status', 'pending')->count(),
            'published' => Testimonial::where('status', 'published')->count(),
            'hidden' => Testimonial::where('status', 'hidden')->count(),
        ];

        return view(
            'admin.testimonials.index',
            compact('testimonials', 'status', 'counts')
        );
    }

    /**
     * Menyetujui dan mempublikasikan ulasan.
     */
    public function publish(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'status' => 'published',
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Ulasan berhasil dipublikasikan.');
    }

    /**
     * Menyembunyikan ulasan dari halaman publik.
     */
    public function hide(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'status' => 'hidden',
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Ulasan berhasil disembunyikan dari publik.');
    }
}
