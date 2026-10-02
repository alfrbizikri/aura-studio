<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('services')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('pages.branches.index', compact('branches'));
    }

    public function show(Branch $branch)
{
    abort_if($branch->status !== 'active', 404);

    $branch->load([
        'services' => function ($query) {
            $query->where('services.status', 'active');
        },

        'photographers' => function ($query) {
            $query->where('status', 'active');
        },

        'studioRooms' => function ($query) {
            $query->where('status', 'active');
        },
    ]);

    return view('pages.branches.show', compact('branch'));
}
}