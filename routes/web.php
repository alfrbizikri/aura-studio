<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PhotographerController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\CustomerAuthController;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/layanan', [ServiceController::class, 'index'])
    ->name('services.index');

Route::get('/layanan/{service:slug}', [ServiceController::class, 'show'])
    ->name('services.show');

Route::get('/paket', [PackageController::class, 'index'])
    ->name('packages.index');

Route::get('/paket/{package}', [PackageController::class, 'show'])
    ->name('packages.show');

Route::get('/cabang', [BranchController::class, 'index'])
    ->name('branches.index');

Route::get('/cabang/{branch}', [BranchController::class, 'show'])
    ->name('branches.show');

Route::get('/fotografer', [PhotographerController::class, 'index'])
    ->name('photographers.index');

Route::get('/fotografer/{photographer}', [PhotographerController::class, 'show'])
    ->name('photographers.show');

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('branches', AdminBranchController::class)
            ->except('show');
    });

Route::get('/galeri', [GalleryController::class, 'index'])
    ->name('gallery.index');

Route::get('/tentang-kami', [AboutController::class, 'index'])
    ->name('about');

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        CustomerAuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        CustomerAuthController::class,
        'login'
    ])->name('login.process');

    Route::get('/register', [
        CustomerAuthController::class,
        'showRegister'
    ])->name('register');

    Route::post('/register', [
        CustomerAuthController::class,
        'register'
    ])->name('register.process');

});


Route::post('/logout', [
    CustomerAuthController::class,
    'logout'
])
    ->middleware('auth')
    ->name('logout');