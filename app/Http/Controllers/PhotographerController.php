<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Photographer;
use Illuminate\Http\Request;

class PhotographerController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')
            ->orderBy('name')
            ->get();

        $specializations = Photographer::query()
            ->where('status', 'active')
            ->whereHas('branch', function ($query) {
                $query->where('status', 'active');
            })
            ->whereNotNull('specialization')
            ->where('specialization', '!=', '')
            ->distinct()
            ->orderBy('specialization')
            ->pluck('specialization');

        $photographers = Photographer::with('branch')
            ->where('status', 'active')

            // Jangan tampilkan fotografer dari cabang nonaktif
            ->whereHas('branch', function ($query) {
                $query->where('status', 'active');
            })

            // Filter cabang
            ->when(
                $request->filled('branch'),
                function ($query) use ($request) {
                    $query->where(
                        'branch_id',
                        $request->branch
                    );
                }
            )

            // Filter spesialisasi
            ->when(
                $request->filled('specialization'),
                function ($query) use ($request) {
                    $query->where(
                        'specialization',
                        $request->specialization
                    );
                }
            )

            ->orderBy('name')
            ->get();

        return view(
            'pages.photographers.index',
            compact(
                'photographers',
                'branches',
                'specializations'
            )
        );
    }

    public function show(Photographer $photographer)
    {
        $photographer->load('branch');

        abort_if(
            $photographer->status !== 'active'
            || !$photographer->branch
            || $photographer->branch->status !== 'active',
            404
        );

        return view(
            'pages.photographers.show',
            compact('photographer')
        );
    }
}