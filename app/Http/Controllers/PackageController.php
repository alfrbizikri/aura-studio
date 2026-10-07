<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Service;

class PackageController extends Controller
{
    /**
     * Menampilkan seluruh paket aktif
     * yang berasal dari layanan aktif.
     */
    public function index()
    {
        $packages = Package::with('service')
            ->where('is_active', true)
            ->whereHas('service', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('service_id')
            ->orderBy('price')
            ->get();

        $services = Service::where('status', 'active')
            ->orderBy('id')
            ->get();

        return view(
            'pages.packages.index',
            compact(
                'packages',
                'services'
            )
        );
    }


    /**
     * Menampilkan detail satu paket.
     */
    public function show(Package $package)
    {
        /*
        |--------------------------------------------------------------------------
        | Load layanan
        |--------------------------------------------------------------------------
        */

        $package->load('service');

        $service = $package->service;


        /*
        |--------------------------------------------------------------------------
        | Proteksi halaman publik
        |--------------------------------------------------------------------------
        |
        | Paket tidak aktif tidak boleh dapat dibuka
        | langsung melalui URL.
        |
        | Paket dari layanan inactive juga tidak boleh
        | ditampilkan pada halaman publik.
        |
        */

        abort_unless(
            $package->is_active
            && $service
            && $service->status === 'active',
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Cabang
        |--------------------------------------------------------------------------
        |
        | Dua status harus aktif:
        |
        | 1. branches.status
        | 2. branch_service.status
        |
        */

        $branches = $service->branches()
            ->where('branches.status', 'active')
            ->wherePivot('status', 'active')
            ->orderBy('branches.name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Gallery
        |--------------------------------------------------------------------------
        |
        | Status tabel galleries:
        |
        | published
        | hidden
        |
        | Bukan active / inactive.
        |
        */

        $galleries = $service->galleries()
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Paket lain dalam layanan yang sama
        |--------------------------------------------------------------------------
        */

        $relatedPackages = Package::where(
                'service_id',
                $package->service_id
            )
            ->where('id', '!=', $package->id)
            ->where('is_active', true)
            ->orderBy('price')
            ->take(3)
            ->get();


        return view(
            'pages.packages.show',
            compact(
                'package',
                'service',
                'branches',
                'galleries',
                'relatedPackages'
            )
        );
    }
}