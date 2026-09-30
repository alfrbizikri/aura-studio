<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('service')
            ->where('is_active', true)
            ->get();

        return view('pages.packages.index', compact('packages'));
    }

    public function show(Package $package)
    {
        $package->load('service');

        return view('pages.packages.show', compact('package'));
    }
}