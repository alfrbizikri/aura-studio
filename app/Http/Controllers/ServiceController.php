<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Menampilkan semua layanan aktif.
     */
    public function index()
    {
        $services = Service::where('status', 'active')
            ->with([
                'packages' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('price');
                },
            ])
            ->orderBy('id')
            ->get();

        return view(
            'pages.services.index',
            compact('services')
        );
    }


    /**
     * Menampilkan detail layanan.
     */
    public function show(Service $service)
    {
        /*
        |--------------------------------------------------------------------------
        | Proteksi service inactive
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $service->status === 'active',
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Paket aktif
        |--------------------------------------------------------------------------
        */

        $service->load([
            'packages' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('price');
            },
        ]);


        /*
        |--------------------------------------------------------------------------
        | Cabang aktif yang benar-benar menyediakan layanan
        |--------------------------------------------------------------------------
        |
        | Jangan hanya melihat status cabang.
        |
        | Pivot branch_service juga memiliki:
        |
        | status = active / inactive
        |
        */

        $branches = $service->branches()
            ->where('branches.status', 'active')
            ->wherePivot('status', 'active')
            ->orderBy('branches.name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Gallery Published
        |--------------------------------------------------------------------------
        */

        $galleries = $service->galleries()
            ->where('status', 'published')
            ->latest()
            ->take(9)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Fotografer
        |--------------------------------------------------------------------------
        |
        | Hanya dimuat apabila layanan memang membutuhkan
        | fotografer.
        |
        | Fotografer juga dibatasi pada cabang-cabang
        | yang menyediakan layanan tersebut.
        |
        */

        $photographers = collect();

        if (
            $service->requires_photographer
            && $branches->isNotEmpty()
        ) {
            $photographers = Photographer::whereIn(
                    'branch_id',
                    $branches->pluck('id')
                )
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Layanan lainnya
        |--------------------------------------------------------------------------
        */

        $otherServices = Service::where(
                'status',
                'active'
            )
            ->where(
                'id',
                '!=',
                $service->id
            )
            ->orderBy('name')
            ->get();


        return view(
            'pages.services.show',
            compact(
                'service',
                'branches',
                'galleries',
                'photographers',
                'otherServices'
            )
        );
    }
}