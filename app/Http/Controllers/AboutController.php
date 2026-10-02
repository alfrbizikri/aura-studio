<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class AboutController extends Controller
{
    public function index()
    {
        $branches = Branch::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'pages.about',
            compact('branches')
        );
    }
}