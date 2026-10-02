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

        $photographers = Photographer::with('branch')
            ->where('status', 'active')
            ->when(
                $request->filled('branch'),
                function ($query) use ($request) {
                    $query->where('branch_id', $request->branch);
                }
            )
            ->orderBy('name')
            ->get();

        return view(
            'pages.photographers.index',
            compact('photographers', 'branches')
        );
    }

    public function show(Photographer $photographer)
    {
        abort_if($photographer->status !== 'active', 404);

        $photographer->load('branch');

        return view(
            'pages.photographers.show',
            compact('photographer')
        );
    }
}