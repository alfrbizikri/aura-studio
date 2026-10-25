<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Layanan
        |--------------------------------------------------------------------------
        */

        $services = Service::where('status', 'active')
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Paket Pilihan
        |--------------------------------------------------------------------------
        |
        | Mengambil satu paket aktif dari setiap layanan.
        | Dengan begitu Home tidak menampilkan 3 paket
        | dari layanan yang sama.
        |
        */

        $servicesWithPackages = Service::with([
            'packages' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('price');
            }
        ])
            ->where('status', 'active')
            ->get();

        $packages = $servicesWithPackages
            ->map(function ($service) {
                return $service->packages->first();
            })
            ->filter()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Cabang
        |--------------------------------------------------------------------------
        */

        $branches = Branch::where('status', 'active')
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Gallery
        |--------------------------------------------------------------------------
        */

        $galleries = Gallery::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Testimoni dan Statistik Rating
        |--------------------------------------------------------------------------
        */

        // Menghitung jumlah ulasan publik dan rata-rata rating.
        $reviewStats = Testimonial::query()
            ->where('status', 'published')
            ->selectRaw(
                'COUNT(*) AS total, COALESCE(AVG(rating), 0) AS average_rating'
            )
            ->first();

        $publishedTestimonialCount = (int) ($reviewStats->total ?? 0);
        $averageRating = (float) ($reviewStats->average_rating ?? 0);

        // Mengambil maksimal 10 ulasan terbaru yang sudah dipublikasikan.
        $testimonials = Testimonial::with([
            'booking.user',
            'booking.package',
        ])
            ->where('status', 'published')
            ->latest()
            ->take(10)
            ->get();


        return view('pages.home', compact(
            'services',
            'packages',
            'branches',
            'galleries',
            'testimonials',
            'publishedTestimonialCount',
            'averageRating'
        ));
    }
}