<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        // Layanan aktif untuk filter galeri
        $services = Service::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Hanya galeri published yang tampil ke customer
        $galleries = Gallery::with('service')
            ->where('status', 'published')
            ->whereHas('service', function ($query) {
                $query->where('status', 'active');
            })
            ->when(
                $request->filled('service'),
                function ($query) use ($request) {
                    $query->where(
                        'service_id',
                        $request->integer('service')
                    );
                }
            )
            ->latest()
            ->get();

        return view(
            'pages.gallery.index',
            compact('galleries', 'services')
        );
    }
}