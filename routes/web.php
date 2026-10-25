<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PhotographerController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TestimonialController;

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerPasswordResetController;

use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\StudioRoomController as AdminStudioRoomController;
use App\Http\Controllers\Admin\PhotographerController as AdminPhotographerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [
    HomeController::class,
    'index'
])->name('home');


Route::get('/ulasan', [
    TestimonialController::class,
    'index',
])->name('testimonials.index');


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

    Route::post('/riwayat-booking/{booking}/ulasan', [
        CustomerBookingController::class,
        'storeTestimonial'
    ])->name('customer.bookings.testimonial.store');

    // Booking Wizard - Step 1: Pilihan
    Route::get('/booking/mulai/{package?}', [
        CustomerBookingController::class,
        'create'
    ])->name('customer.bookings.create');

    Route::post('/booking/pilihan', [
        CustomerBookingController::class,
        'storeSelection'
    ])->name('customer.bookings.selection.store');


    // Step 2: Fotografer
    Route::get('/booking/fotografer', [
        CustomerBookingController::class,
        'photographer'
    ])->name('customer.bookings.photographer');

    Route::post('/booking/fotografer', [
        CustomerBookingController::class,
        'storePhotographer'
    ])->name('customer.bookings.photographer.store');


    // Step 3: Jadwal
    Route::get('/booking/jadwal', [
        CustomerBookingController::class,
        'schedule'
    ])->name('customer.bookings.schedule');

    Route::post('/booking/jadwal', [
        CustomerBookingController::class,
        'storeSchedule'
    ])->name('customer.bookings.schedule.store');


    // Step 4: Detail Pemesanan
    Route::get('/booking/detail', [
        CustomerBookingController::class,
        'detail'
    ])->name('customer.bookings.detail');

    Route::post('/booking/detail', [
        CustomerBookingController::class,
        'storeDetail'
    ])->name('customer.bookings.detail.store');


    // Step 5: Konfirmasi
    Route::get('/booking/konfirmasi', [
        CustomerBookingController::class,
        'confirmation'
    ])->name('customer.bookings.confirmation');

    Route::post('/booking/konfirmasi', [
        CustomerBookingController::class,
        'store'
    ])->name('customer.bookings.store');


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
| ADMIN AUTH
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/login', [
            AdminAuthController::class,
            'showLogin'
        ])->name('login');

        Route::post('/login', [
            AdminAuthController::class,
            'login'
        ])->name('login.process');
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
    ->middleware('admin')
    ->group(function () {

        Route::resource(
            'branches',
            AdminBranchController::class
        )->except('show');

        Route::resource(
            'galleries',
            AdminGalleryController::class
        )->except('show');

        Route::resource(
            'packages',
            AdminPackageController::class
        )->except('show');

        Route::resource(
            'studio-rooms',
            AdminStudioRoomController::class
        )->except('show');

        Route::resource(
            'services',
            AdminServiceController::class
        )->except('show');

        Route::resource(
            'photographers',
            AdminPhotographerController::class
        )->except('show');

        Route::get(
            'schedules',
            [AdminScheduleController::class, 'index']
        )->name('schedules.index');

        Route::get(
            'bookings',
            [AdminBookingController::class, 'index']
        )->name('bookings.index');

        Route::get(
            'bookings/{booking}',
            [AdminBookingController::class, 'show']
        )->name('bookings.show');

        Route::get(
            'payments',
            [AdminPaymentController::class, 'index']
        )->name('payments.index');

        Route::get(
            'payments/{payment}',
            [AdminPaymentController::class, 'show']
        )->name('payments.show');

        Route::patch(
            'payments/{payment}/approve',
            [AdminPaymentController::class, 'approve']
        )->name('payments.approve');

        Route::patch(
            'payments/{payment}/reject',
            [AdminPaymentController::class, 'reject']
        )->name('payments.reject');

        Route::get(
            'customers',
            [AdminCustomerController::class, 'index']
        )->name('customers.index');

        Route::get(
            'customers/{user}',
            [AdminCustomerController::class, 'show']
        )->name('customers.show');

        Route::post('/logout', [
            AdminAuthController::class,
            'logout'
        ])->name('logout');

        Route::get('/laporan', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])
            ->name('reports.pdf');

        Route::patch('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::patch('/bookings/{booking}/complete', [AdminBookingController::class, 'complete'])
            ->name('bookings.complete');

        // MANAJEMEN ULASAN
        Route::get('/ulasan', [
            AdminTestimonialController::class,
            'index',
        ])->name('testimonials.index');

        Route::patch('/ulasan/{testimonial}/publish', [
            AdminTestimonialController::class,
            'publish',
        ])->name('testimonials.publish');

        Route::patch('/ulasan/{testimonial}/hide', [
            AdminTestimonialController::class,
            'hide',
        ])->name('testimonials.hide');
    });
