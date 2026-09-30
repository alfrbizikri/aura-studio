<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 'active')
            ->get();

        return view('pages.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        $service->load([
            'packages' => function ($query) {
                $query->where('is_active', true);
            }
        ]);

        return view('pages.services.show', compact('service'));
    }
}