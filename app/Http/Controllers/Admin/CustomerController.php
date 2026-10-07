<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('bookings')
            ->orderBy('name')
            ->get();

        return view(
            'admin.customers.index',
            compact('customers')
        );
    }

    public function show(User $user)
    {
        abort_unless(
            $user->role === 'customer',
            404
        );

        $user->load([
            'bookings' => function ($query) {
                $query
                    ->with([
                        'package.service',
                        'payment',
                        'schedules.branch',
                    ])
                    ->latest();
            },
        ]);

        return view(
            'admin.customers.show',
            compact('user')
        );
    }
}