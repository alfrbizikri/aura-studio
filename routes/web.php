<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BranchController;

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
