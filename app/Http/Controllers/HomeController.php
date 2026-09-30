<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 'active')
            ->take(3)
            ->get();

        $packages = Package::where('is_active', true)
            ->take(3)
            ->get();

        $branches = Branch::where('status', 'active')
            ->take(3)
            ->get();

        $galleries = Gallery::where('status', 'published')
            ->take(6)
            ->get();

        return view('pages.home', compact(
            'services',
            'packages',
            'branches',
            'galleries'
        ));
    }
}