<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::with([
            'booking.user',
            'booking.package',
        ])
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        $publishedCount = Testimonial::where('status', 'published')
            ->count();

        $averageRating = Testimonial::where('status', 'published')
            ->avg('rating') ?? 0;

        return view('pages.testimonials.index', compact(
            'testimonials',
            'publishedCount',
            'averageRating'
        ));
    }
}