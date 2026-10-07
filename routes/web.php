<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PhotographerController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AboutController;

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerPasswordResetController;

use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [
    HomeController::class,
    'index'
])->name('home');


/*
|--------------------------------------------------------------------------
| LAYANAN
|--------------------------------------------------------------------------
*/

Route::get('/layanan', [
    ServiceController::class,
    'index'
])->name('services.index');

Route::get('/layanan/{service:slug}', [
    ServiceController::class,
    'show'
])->name('services.show');


/*
|--------------------------------------------------------------------------
| PAKET
|--------------------------------------------------------------------------
*/

Route::get('/paket', [
    PackageController::class,
    'index'
])->name('packages.index');

Route::get('/paket/{package}', [
    PackageController::class,
    'show'
])->name('packages.show');


/*
|--------------------------------------------------------------------------
| CABANG
|--------------------------------------------------------------------------
*/

Route::get('/cabang', [
    BranchController::class,
    'index'
])->name('branches.index');

Route::get('/cabang/{branch}', [
    BranchController::class,
    'show'
])->name('branches.show');


/*
|--------------------------------------------------------------------------
| FOTOGRAFER
|--------------------------------------------------------------------------
*/

Route::get('/fotografer', [
    PhotographerController::class,
    'index'
])->name('photographers.index');

Route::get('/fotografer/{photographer}', [
    PhotographerController::class,
    'show'
])->name('photographers.show');


/*
|--------------------------------------------------------------------------
| GALERI
|--------------------------------------------------------------------------
*/

Route::get('/galeri', [
    GalleryController::class,
    'index'
])->name('gallery.index');


/*
|--------------------------------------------------------------------------
| TENTANG KAMI
|--------------------------------------------------------------------------
*/

Route::get('/tentang-kami', [
    AboutController::class,
    'index'
])->name('about');


/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
|
| Hanya untuk pengguna yang belum login.
|
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [
        CustomerAuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        CustomerAuthController::class,
        'login'
    ])->name('login.process');


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    Route::get('/register', [
        CustomerAuthController::class,
        'showRegister'
    ])->name('register');

    Route::post('/register', [
        CustomerAuthController::class,
        'register'
    ])->name('register.process');


    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::get('/lupa-password', [
        CustomerPasswordResetController::class,
        'showForgotForm'
    ])->name('password.request');

    Route::post('/lupa-password', [
        CustomerPasswordResetController::class,
        'sendResetLink'
    ])->name('password.email');


    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::get('/reset-password/{token}', [
        CustomerPasswordResetController::class,
        'showResetForm'
    ])->name('password.reset');

    Route::post('/reset-password', [
        CustomerPasswordResetController::class,
        'resetPassword'
    ])->name('password.update');
});


/*
|--------------------------------------------------------------------------
| CUSTOMER - AUTHENTICATED
|--------------------------------------------------------------------------
|
| Semua route di bawah hanya dapat diakses customer
| yang sudah login.
|
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        CustomerAuthController::class,
        'logout'
    ])->name('logout');


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profil', [
        CustomerProfileController::class,
        'edit'
    ])->name('customer.profile.edit');

    Route::put('/profil', [
        CustomerProfileController::class,
        'update'
    ])->name('customer.profile.update');


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT BOOKING
    |--------------------------------------------------------------------------
    */

    Route::get('/riwayat-booking', [
        CustomerBookingController::class,
        'index'
    ])->name('customer.bookings.index');


    /*
    |--------------------------------------------------------------------------
    | DETAIL BOOKING
    |--------------------------------------------------------------------------
    */

    Route::get('/riwayat-booking/{booking}', [
        CustomerBookingController::class,
        'show'
    ])->name('customer.bookings.show');


    /*
    |--------------------------------------------------------------------------
    | PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    Route::get('/riwayat-booking/{booking}/pembayaran', [
        CustomerPaymentController::class,
        'create'
    ])->name('customer.payments.create');

    Route::post('/riwayat-booking/{booking}/pembayaran', [
        CustomerPaymentController::class,
        'store'
    ])->name('customer.payments.store');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
|
| Untuk sementara route CRUD admin tetap dipertahankan.
| Setelah login + middleware admin milik modul admin selesai,
| group ini harus diberi middleware admin.
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource(
            'branches',
            AdminBranchController::class
        )->except('show');

        Route::resource(
            'galleries',
            AdminGalleryController::class
        )->except('show');
    });