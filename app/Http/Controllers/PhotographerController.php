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

        // Semua fotografer aktif dikirim; pencarian & filter dijalankan di halaman.
        // ?branch=ID tetap didukung sebagai pilihan awal filter cabang.
        $photographers = Photographer::with('branch')
            ->where('status', 'active')
            ->whereHas('branch', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('name')
            ->get();

        $selectedBranch = $request->input('branch');

        return view('pages.photographers.index', compact('photographers', 'branches', 'selectedBranch'));
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

        $photographer->load([
            'branch.services' => function ($query) {
                $query
                    ->where('services.status', 'active')
                    ->wherePivot('status', 'active');
            }
        ]);

        $otherPhotographers = Photographer::where('branch_id', $photographer->branch_id)
            ->where('id', '!=', $photographer->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->take(4)
            ->get();

        return view('pages.photographers.show', compact('photographer', 'otherPhotographers'));
    }
}
