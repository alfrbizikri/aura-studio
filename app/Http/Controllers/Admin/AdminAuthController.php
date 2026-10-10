<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()
                    ->route('admin.branches.index');
            }

            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'Silakan logout dari akun customer terlebih dahulu.'
                );
        }

        return view('admin.auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        if (Auth::attempt($credentials)) {

            if (Auth::user()->role !== 'admin') {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors([
                        'email' => 'Akun ini bukan akun admin.',
                    ])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()
                ->route('admin.branches.index')
                ->with(
                    'success',
                    'Login admin berhasil.'
                );
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])
            ->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with(
                'success',
                'Anda telah logout dari Admin Aura Studio.'
            );
    }
}