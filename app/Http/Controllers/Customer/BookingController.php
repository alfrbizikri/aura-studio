<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Photographer;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\BookingAvailabilityService;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
            'package',
            'payment',
            'schedules',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'customer.bookings.index',
            compact('bookings')
        );
    }

    /**
     * STEP 1
     * Pilih layanan, cabang, dan paket.
     */
    public function create(?Package $package = null)
    {
        if ($package) {
            $package->load('service');

            abort_unless(
                $package->is_active
                && $package->service
                && $package->service->status === 'active',
                404
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Layanan aktif
        |--------------------------------------------------------------------------
        */
        $services = Service::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Cabang aktif + layanan aktif pada pivot
        |--------------------------------------------------------------------------
        */
        $branches = Branch::query()
            ->where('status', 'active')
            ->with([
                'services' => function ($query) {
                    $query
                        ->where('services.status', 'active')
                        ->wherePivot('status', 'active');
                },
            ])
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Semua paket aktif
        |--------------------------------------------------------------------------
        |
        | Kita ambil seluruh paket aktif agar user masih bisa mengganti layanan
        | dan paket pada Step 1.
        |
        */
        $packages = Package::query()
            ->with('service')
            ->where('is_active', true)
            ->whereHas('service', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('price')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Draft lama
        |--------------------------------------------------------------------------
        */
        $draft = session('booking_draft', []);

        /*
        |--------------------------------------------------------------------------
        | Default pilihan
        |--------------------------------------------------------------------------
        |
        | Jika user datang dari Detail Paket, package tersebut langsung menjadi
        | pilihan awal.
        |
        */
        $selectedPackageId =
            $draft['package_id']
            ?? $package?->id;

        $selectedPackage = $selectedPackageId
            ? $packages->firstWhere('id', $selectedPackageId)
            : null;

        $selectedServiceId =
            $draft['service_id']
            ?? $selectedPackage?->service_id;

        $selectedBranchId =
            $draft['branch_id']
            ?? null;

        return view(
            'customer.bookings.create',
            compact(
                'services',
                'branches',
                'packages',
                'package',
                'selectedPackageId',
                'selectedServiceId',
                'selectedBranchId'
            )
        );
    }

    /**
     * Simpan pilihan Step 1 ke session.
     */
    public function storeSelection(Request $request)
    {
        $validated = $request->validate([
            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'package_id' => [
                'required',
                'integer',
                'exists:packages,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi layanan
        |--------------------------------------------------------------------------
        */
        $service = Service::query()
            ->where('id', $validated['service_id'])
            ->where('status', 'active')
            ->first();

        if (!$service) {
            return back()
                ->withInput()
                ->withErrors([
                    'service_id' =>
                        'Layanan yang dipilih sedang tidak tersedia.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi paket
        |--------------------------------------------------------------------------
        */
        $selectedPackage = Package::query()
            ->where('id', $validated['package_id'])
            ->where('service_id', $service->id)
            ->where('is_active', true)
            ->first();

        if (!$selectedPackage) {
            return back()
                ->withInput()
                ->withErrors([
                    'package_id' =>
                        'Paket tidak sesuai dengan layanan yang dipilih.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi cabang
        |--------------------------------------------------------------------------
        |
        | Cabang wajib aktif dan benar-benar menyediakan layanan tersebut
        | melalui branch_service dengan status active.
        |
        */
        $branch = Branch::query()
            ->where('id', $validated['branch_id'])
            ->where('status', 'active')
            ->whereHas('services', function ($query) use ($service) {
                $query
                    ->where('services.id', $service->id)
                    ->where('services.status', 'active')
                    ->where('branch_service.status', 'active');
            })
            ->first();

        if (!$branch) {
            return back()
                ->withInput()
                ->withErrors([
                    'branch_id' =>
                        'Cabang yang dipilih tidak menyediakan layanan tersebut.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan draft Booking
        |--------------------------------------------------------------------------
        */
        session([
            'booking_draft' => [
                'service_id' => $service->id,
                'branch_id' => $branch->id,
                'package_id' => $selectedPackage->id,

                // Step berikutnya belum dipilih.
                'photographer_id' => null,
                'schedule_id' => null,
                'number_of_people' => null,
                'location_address' => null,
                'location_notes' => null,
                'notes' => null,
            ],
        ]);

        return redirect()
            ->route('customer.bookings.photographer');
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2–5
    |--------------------------------------------------------------------------
    | Akan kita isi setelah Step 1 selesai.
    |--------------------------------------------------------------------------
    */

    public function photographer()
    {
        $draft = session('booking_draft');

        if (
            !$draft
            || empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
        ) {
            return redirect()
                ->route('packages.index')
                ->with('error', 'Silakan mulai proses booking dari pemilihan paket.');
        }

        $service = Service::query()
            ->where('id', $draft['service_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $branch = Branch::query()
            ->where('id', $draft['branch_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $package = Package::query()
            ->where('id', $draft['package_id'])
            ->where('service_id', $service->id)
            ->where('is_active', true)
            ->firstOrFail();

        $photographers = Photographer::query()
            ->where('branch_id', $branch->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $selectedPhotographerId =
            old('photographer_id')
            ?? (
                isset($draft['photographer_id'])
                && $draft['photographer_id']
                ? (string) $draft['photographer_id']
                : 'auto'
            );

        return view(
            'customer.bookings.photographer',
            compact(
                'service',
                'branch',
                'package',
                'photographers',
                'selectedPhotographerId'
            )
        );
    }

    public function storePhotographer(Request $request)
    {
        $draft = session('booking_draft');

        if (
            !$draft
            || empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
        ) {
            return redirect()
                ->route('packages.index')
                ->with('error', 'Data booking tidak ditemukan. Silakan mulai kembali.');
        }

        $request->validate([
            'photographer_id' => [
                'required',
            ],
        ]);

        $photographerId = null;

        if ($request->photographer_id !== 'auto') {

            if (!ctype_digit((string) $request->photographer_id)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'photographer_id' => 'Pilihan fotografer tidak valid.',
                    ]);
            }

            $photographer = Photographer::query()
                ->where('id', $request->photographer_id)
                ->where('branch_id', $draft['branch_id'])
                ->where('status', 'active')
                ->first();

            if (!$photographer) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'photographer_id' =>
                            'Fotografer yang dipilih tidak tersedia pada cabang tersebut.',
                    ]);
            }

            $photographerId = $photographer->id;
        }

        $draft['photographer_id'] = $photographerId;
        $draft['photographer_mode'] =
            $photographerId ? 'manual' : 'auto';

        /*
         * Jika kembali dari Step berikutnya dan mengganti fotografer,
         * jadwal lama tidak boleh dipertahankan.
         */
        $draft['schedule_id'] = null;

        session([
            'booking_draft' => $draft,
        ]);

        return redirect()
            ->route('customer.bookings.schedule');
    }

    public function schedule(
        Request $request,
        BookingAvailabilityService $availability
    ) {
        $draft = session(
            'booking_draft',
            []
        );

        if (
            empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
        ) {
            return redirect()
                ->route(
                    'customer.bookings.create'
                )
                ->with(
                    'error',
                    'Silakan lengkapi pilihan booking terlebih dahulu.'
                );
        }

        $service = Service::query()
            ->where(
                'status',
                'active'
            )
            ->findOrFail(
                $draft['service_id']
            );

        $branch = Branch::query()
            ->where(
                'status',
                'active'
            )
            ->findOrFail(
                $draft['branch_id']
            );

        $package = Package::query()
            ->where(
                'is_active',
                true
            )
            ->where(
                'service_id',
                $service->id
            )
            ->findOrFail(
                $draft['package_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Fotografer
        |--------------------------------------------------------------------------
        */
        $photographerId =
            $draft['photographer_id']
            ?? null;

        $photographer = null;

        if ($photographerId) {
            $photographer =
                Photographer::query()
                    ->where(
                        'branch_id',
                        $branch->id
                    )
                    ->where(
                        'status',
                        'active'
                    )
                    ->findOrFail(
                        $photographerId
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Range kalender
        |--------------------------------------------------------------------------
        */
        $minDate =
            today()->startOfDay();

        $maxDate =
            today()
                ->copy()
                ->addYear()
                ->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | Tanggal yang dipilih
        |--------------------------------------------------------------------------
        */
        $requestedDate =
            $request->query(
                'date',
                $draft['schedule_date']
                ?? $minDate->toDateString()
            );

        try {
            $selectedDateObject =
                Carbon::createFromFormat(
                    'Y-m-d',
                    $requestedDate
                )->startOfDay();
        } catch (\Throwable $exception) {
            $selectedDateObject =
                $minDate->copy();
        }

        /*
         * Jangan izinkan tanggal di luar range.
         */
        if (
            $selectedDateObject->lt($minDate)
            || $selectedDateObject->gt($maxDate)
        ) {
            $selectedDateObject =
                $minDate->copy();
        }

        $selectedDate =
            $selectedDateObject
                ->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Kalender bulan
        |--------------------------------------------------------------------------
        */
        $displayMonth =
            $selectedDateObject
                ->copy()
                ->startOfMonth();

        $calendarStart =
            $displayMonth
                ->copy()
                ->startOfWeek(
                    Carbon::MONDAY
                );

        $calendarEnd =
            $displayMonth
                ->copy()
                ->endOfMonth()
                ->endOfWeek(
                    Carbon::SUNDAY
                );

        $calendarDays = collect();

        $calendarCursor =
            $calendarStart->copy();

        while (
            $calendarCursor->lte(
                $calendarEnd
            )
        ) {
            $calendarDays->push(
                $calendarCursor->copy()
            );

            $calendarCursor->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Navigasi bulan sebelumnya
        |--------------------------------------------------------------------------
        */
        $previousMonthDate = null;

        if (
            $displayMonth->gt(
                $minDate
                    ->copy()
                    ->startOfMonth()
            )
        ) {
            $previousCandidate =
                $displayMonth
                    ->copy()
                    ->subMonth()
                    ->startOfMonth();

            if (
                $previousCandidate->lt(
                    $minDate
                )
            ) {
                $previousCandidate =
                    $minDate->copy();
            }

            $previousMonthDate =
                $previousCandidate
                    ->toDateString();
        }

        /*
        |--------------------------------------------------------------------------
        | Navigasi bulan berikutnya
        |--------------------------------------------------------------------------
        */
        $nextMonthDate = null;

        if (
            $displayMonth->lt(
                $maxDate
                    ->copy()
                    ->startOfMonth()
            )
        ) {
            $nextCandidate =
                $displayMonth
                    ->copy()
                    ->addMonth()
                    ->startOfMonth();

            if (
                $nextCandidate->gt(
                    $maxDate
                )
            ) {
                $nextCandidate =
                    $maxDate->copy();
            }

            $nextMonthDate =
                $nextCandidate
                    ->toDateString();
        }

        /*
        |--------------------------------------------------------------------------
        | Availability HANYA untuk tanggal terpilih
        |--------------------------------------------------------------------------
        */
        $availableSlots =
            $availability
                ->generateAvailableSlotsForDate(
                    branch: $branch,
                    service: $service,
                    package: $package,
                    date: $selectedDate,
                    photographerId:
                    $photographerId,
                    intervalMinutes: 30
                );

        return view(
            'customer.bookings.schedule',
            compact(
                'service',
                'branch',
                'package',
                'photographer',
                'draft',
                'availableSlots',
                'selectedDate',
                'selectedDateObject',
                'displayMonth',
                'calendarDays',
                'minDate',
                'maxDate',
                'previousMonthDate',
                'nextMonthDate'
            )
        );
    }


    public function storeSchedule(
        Request $request,
        BookingAvailabilityService $availability
    ) {
        $draft = session('booking_draft', []);

        if (
            empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
        ) {
            return redirect()
                ->route('customer.bookings.create')
                ->with(
                    'error',
                    'Data booking tidak lengkap.'
                );
        }

        $validated = $request->validate([
            'schedule_date' => [
                'required',
                'date_format:Y-m-d',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],
        ]);

        $service = Service::query()
            ->where('status', 'active')
            ->findOrFail($draft['service_id']);

        $branch = Branch::query()
            ->where('status', 'active')
            ->findOrFail($draft['branch_id']);

        $package = Package::query()
            ->where('is_active', true)
            ->where('service_id', $service->id)
            ->findOrFail($draft['package_id']);

        /*
         * Kalau customer memilih fotografer tertentu,
         * service wajib mengecek fotografer tersebut.
         *
         * Kalau mode auto → null.
         */
        $photographerId =
            $draft['photographer_id']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI ULANG SLOT
        |--------------------------------------------------------------------------
        |
        | Jangan percaya slot yang dikirim browser.
        | Engine mengecek lagi availability saat POST.
        |
        */
        $resolvedSlot = $availability->resolveSlot(
            branch: $branch,
            service: $service,
            package: $package,
            date: $validated['schedule_date'],
            startTime: $validated['start_time'],
            photographerId: $photographerId
        );

        if (!$resolvedSlot) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jadwal tersebut sudah tidak tersedia. Silakan pilih waktu lain.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan slot ke draft
        |--------------------------------------------------------------------------
        */
        unset($draft['schedule_id']);

        $draft['schedule_date'] =
            $resolvedSlot['date'];

        $draft['start_time'] =
            $resolvedSlot['start_time'];

        $draft['end_time'] =
            $resolvedSlot['end_time'];

        $draft['assigned_photographer_id'] =
            $resolvedSlot['photographer_id'];

        $draft['studio_room_id'] =
            $resolvedSlot['studio_room_id'];

        session([
            'booking_draft' => $draft,
        ]);

        return redirect()
            ->route('customer.bookings.detail');
    }

    public function detail(
        BookingAvailabilityService $availability
    ) {
        $draft = session('booking_draft', []);

        /*
        |--------------------------------------------------------------------------
        | Draft harus sudah melewati Step 1–3
        |--------------------------------------------------------------------------
        */
        if (
            empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_date'])
            || empty($draft['start_time'])
        ) {
            return redirect()
                ->route('customer.bookings.schedule')
                ->with(
                    'error',
                    'Silakan pilih jadwal terlebih dahulu.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Data utama
        |--------------------------------------------------------------------------
        */
        $service = Service::query()
            ->where('status', 'active')
            ->findOrFail($draft['service_id']);

        $branch = Branch::query()
            ->where('status', 'active')
            ->findOrFail($draft['branch_id']);

        $package = Package::query()
            ->where('is_active', true)
            ->where('service_id', $service->id)
            ->findOrFail($draft['package_id']);


        /*
        |--------------------------------------------------------------------------
        | Validasi ulang slot
        |--------------------------------------------------------------------------
        |
        | Slot bisa saja berubah sejak customer memilihnya.
        |
        | Kalau fotografer dipilih manual, fotografer itu harus
        | tetap tersedia.
        |
        | Kalau mode otomatis, engine boleh memilih fotografer
        | aktif lain yang masih tersedia.
        |
        */
        $resolvedSlot = $availability->resolveSlot(
            branch: $branch,
            service: $service,
            package: $package,
            date: $draft['schedule_date'],
            startTime: $draft['start_time'],
            photographerId:
            $draft['photographer_id']
            ?? null
        );


        if (!$resolvedSlot) {

            /*
             * Reset hanya bagian jadwal.
             * Pilihan layanan/cabang/paket/fotografer tetap ada.
             */
            $draft['schedule_date'] = null;
            $draft['start_time'] = null;
            $draft['end_time'] = null;
            $draft['assigned_photographer_id'] = null;
            $draft['studio_room_id'] = null;

            session([
                'booking_draft' => $draft,
            ]);

            return redirect()
                ->route('customer.bookings.schedule')
                ->with(
                    'error',
                    'Jadwal yang dipilih sudah tidak tersedia. Silakan pilih waktu lain.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan hasil resolve terbaru
        |--------------------------------------------------------------------------
        */
        $draft['schedule_date'] =
            $resolvedSlot['date'];

        $draft['start_time'] =
            $resolvedSlot['start_time'];

        $draft['end_time'] =
            $resolvedSlot['end_time'];

        $draft['assigned_photographer_id'] =
            $resolvedSlot['photographer_id'];

        $draft['studio_room_id'] =
            $resolvedSlot['studio_room_id'];

        session([
            'booking_draft' => $draft,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Resource untuk ditampilkan
        |--------------------------------------------------------------------------
        */
        $photographer =
            $resolvedSlot['photographer'];

        $studioRoom =
            $resolvedSlot['studio_room'];

        $customer =
            Auth::user();


        return view(
            'customer.bookings.detail',
            compact(
                'service',
                'branch',
                'package',
                'photographer',
                'studioRoom',
                'customer',
                'draft'
            )
        );
    }


    public function storeDetail(
        Request $request,
        BookingAvailabilityService $availability
    ) {
        $draft = session('booking_draft', []);

        /*
        |--------------------------------------------------------------------------
        | Pastikan Step 1–3 lengkap
        |--------------------------------------------------------------------------
        */
        if (
            empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_date'])
            || empty($draft['start_time'])
        ) {
            return redirect()
                ->route('customer.bookings.schedule')
                ->with(
                    'error',
                    'Silakan pilih jadwal terlebih dahulu.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Data utama
        |--------------------------------------------------------------------------
        */
        $service = Service::query()
            ->where('status', 'active')
            ->findOrFail($draft['service_id']);

        $branch = Branch::query()
            ->where('status', 'active')
            ->findOrFail($draft['branch_id']);

        $package = Package::query()
            ->where('is_active', true)
            ->where('service_id', $service->id)
            ->findOrFail($draft['package_id']);


        /*
        |--------------------------------------------------------------------------
        | Validasi ulang availability
        |--------------------------------------------------------------------------
        */
        $resolvedSlot = $availability->resolveSlot(
            branch: $branch,
            service: $service,
            package: $package,
            date: $draft['schedule_date'],
            startTime: $draft['start_time'],
            photographerId:
            $draft['photographer_id']
            ?? null
        );


        if (!$resolvedSlot) {

            $draft['schedule_date'] = null;
            $draft['start_time'] = null;
            $draft['end_time'] = null;
            $draft['assigned_photographer_id'] = null;
            $draft['studio_room_id'] = null;

            session([
                'booking_draft' => $draft,
            ]);

            return redirect()
                ->route('customer.bookings.schedule')
                ->with(
                    'error',
                    'Jadwal tersebut sudah tidak tersedia. Silakan pilih jadwal lain.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Batas jumlah orang
        |--------------------------------------------------------------------------
        */
        $maxPeople =
            $package->max_people
            ?: 255;


        /*
        |--------------------------------------------------------------------------
        | Validasi form detail
        |--------------------------------------------------------------------------
        */
        $rules = [
            'number_of_people' => [
                'required',
                'integer',
                'min:1',
                'max:' . $maxPeople,
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'location_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];


        /*
         * Alamat hanya wajib untuk layanan
         * Photography On Location.
         */
        if ($service->is_on_location) {

            $rules['location_address'] = [
                'required',
                'string',
                'max:500',
            ];

        } else {

            $rules['location_address'] = [
                'nullable',
                'string',
                'max:500',
            ];
        }


        $validated =
            $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | Update slot berdasarkan resolve terbaru
        |--------------------------------------------------------------------------
        */
        $draft['end_time'] =
            $resolvedSlot['end_time'];

        $draft['assigned_photographer_id'] =
            $resolvedSlot['photographer_id'];

        $draft['studio_room_id'] =
            $resolvedSlot['studio_room_id'];


        /*
        |--------------------------------------------------------------------------
        | Detail customer
        |--------------------------------------------------------------------------
        */
        $draft['number_of_people'] =
            (int) $validated['number_of_people'];

        $draft['notes'] =
            $validated['notes']
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | Lokasi
        |--------------------------------------------------------------------------
        */
        if ($service->is_on_location) {

            $draft['location_address'] =
                $validated['location_address'];

            $draft['location_notes'] =
                $validated['location_notes']
                ?? null;

        } else {

            $draft['location_address'] =
                null;

            $draft['location_notes'] =
                null;
        }


        session([
            'booking_draft' => $draft,
        ]);


        return redirect()
            ->route(
                'customer.bookings.confirmation'
            );
    }

    public function confirmation(
        BookingAvailabilityService $availability
    ) {
        $draft = session('booking_draft', []);

        if (
            empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_date'])
            || empty($draft['start_time'])
            || empty($draft['number_of_people'])
        ) {
            return redirect()
                ->route('customer.bookings.create')
                ->with(
                    'error',
                    'Data booking belum lengkap.'
                );
        }

        $service = Service::query()
            ->where('status', 'active')
            ->findOrFail($draft['service_id']);

        $branch = Branch::query()
            ->where('status', 'active')
            ->findOrFail($draft['branch_id']);

        $package = Package::query()
            ->where('is_active', true)
            ->where('service_id', $service->id)
            ->findOrFail($draft['package_id']);

        /*
        |--------------------------------------------------------------------------
        | Validasi jumlah peserta
        |--------------------------------------------------------------------------
        */
        $maxPeople = $package->max_people ?: 255;

        if (
            (int) $draft['number_of_people'] < 1
            || (int) $draft['number_of_people'] > $maxPeople
        ) {
            return redirect()
                ->route('customer.bookings.detail')
                ->with(
                    'error',
                    'Jumlah peserta tidak sesuai dengan paket.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Pertahankan resource yang sudah ditampilkan di Step 4
        |--------------------------------------------------------------------------
        */
        $photographerId =
            $draft['assigned_photographer_id']
            ?? $draft['photographer_id']
            ?? null;

        $studioRoomId =
            $draft['studio_room_id']
            ?? null;

        $resolvedSlot = $availability->resolveSlot(
            branch: $branch,
            service: $service,
            package: $package,
            date: $draft['schedule_date'],
            startTime: $draft['start_time'],
            photographerId: $photographerId,
            studioRoomId: $studioRoomId
        );

        if (!$resolvedSlot) {

            $draft['schedule_date'] = null;
            $draft['start_time'] = null;
            $draft['end_time'] = null;
            $draft['assigned_photographer_id'] = null;
            $draft['studio_room_id'] = null;

            session([
                'booking_draft' => $draft,
            ]);

            return redirect()
                ->route('customer.bookings.schedule')
                ->with(
                    'error',
                    'Jadwal yang dipilih sudah tidak tersedia. Silakan pilih waktu lain.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Refresh resource dari hasil pengecekan
        |--------------------------------------------------------------------------
        */
        $draft['end_time'] =
            $resolvedSlot['end_time'];

        $draft['assigned_photographer_id'] =
            $resolvedSlot['photographer_id'];

        $draft['studio_room_id'] =
            $resolvedSlot['studio_room_id'];

        session([
            'booking_draft' => $draft,
        ]);

        $photographer =
            $resolvedSlot['photographer'];

        $studioRoom =
            $resolvedSlot['studio_room'];

        $customer = Auth::user();

        return view(
            'customer.bookings.confirmation',
            compact(
                'service',
                'branch',
                'package',
                'photographer',
                'studioRoom',
                'customer',
                'draft'
            )
        );
    }


    public function store(
        Request $request,
        BookingAvailabilityService $availability
    ) {
        $request->validate([
            'confirmation' => [
                'accepted',
            ],
        ], [
            'confirmation.accepted' =>
                'Silakan centang persetujuan sebelum mengonfirmasi booking.',
        ]);

        $draft = session('booking_draft', []);

        if (
            empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_date'])
            || empty($draft['start_time'])
            || empty($draft['number_of_people'])
        ) {
            return redirect()
                ->route('customer.bookings.create')
                ->with(
                    'error',
                    'Data booking belum lengkap.'
                );
        }

        $service = Service::query()
            ->where('status', 'active')
            ->findOrFail($draft['service_id']);

        $package = Package::query()
            ->where('is_active', true)
            ->where('service_id', $service->id)
            ->findOrFail($draft['package_id']);

        $maxPeople =
            $package->max_people
            ?: 255;

        if (
            (int) $draft['number_of_people'] < 1
            || (int) $draft['number_of_people'] > $maxPeople
        ) {
            return redirect()
                ->route('customer.bookings.detail')
                ->with(
                    'error',
                    'Jumlah peserta tidak sesuai dengan paket.'
                );
        }

        try {

            $booking = DB::transaction(
                function () use ($draft, $service, $package, $availability) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lock cabang
                    |--------------------------------------------------------------------------
                    |
                    | Booking pada cabang yang sama akan diproses bergantian.
                    | Ini membantu mencegah race condition / double booking.
                    |
                    */
                    $branch = Branch::query()
                        ->where('id', $draft['branch_id'])
                        ->where('status', 'active')
                        ->lockForUpdate()
                        ->firstOrFail();

                    /*
                    |--------------------------------------------------------------------------
                    | Gunakan resource yang sudah disetujui customer
                    |--------------------------------------------------------------------------
                    */
                    $photographerId =
                        $draft['assigned_photographer_id']
                        ?? $draft['photographer_id']
                        ?? null;

                    $studioRoomId =
                        $draft['studio_room_id']
                        ?? null;

                    /*
                    |--------------------------------------------------------------------------
                    | CEK TERAKHIR
                    |--------------------------------------------------------------------------
                    */
                    $resolvedSlot =
                        $availability->resolveSlot(
                            branch: $branch,
                            service: $service,
                            package: $package,
                            date: $draft['schedule_date'],
                            startTime: $draft['start_time'],
                            photographerId:
                            $photographerId,
                            studioRoomId:
                            $studioRoomId
                        );

                    if (!$resolvedSlot) {
                        throw ValidationException::withMessages([
                            'schedule' =>
                                'Jadwal tersebut baru saja terisi atau tidak lagi tersedia.',
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Cek legacy schedule available
                    |--------------------------------------------------------------------------
                    |
                    | Karena sebelumnya project menggunakan row available,
                    | kalau ada row lama yang persis sama kita pakai row tersebut
                    | daripada membuat duplikat.
                    |
                    */
                    $schedule = Schedule::query()
                        ->where('branch_id', $branch->id)
                        ->where('service_id', $service->id)
                        ->whereDate(
                            'schedule_date',
                            $resolvedSlot['date']
                        )
                        ->where(
                            'start_time',
                            $resolvedSlot['start_time']
                        )
                        ->where(
                            'end_time',
                            $resolvedSlot['end_time']
                        )
                        ->where(
                            'photographer_id',
                            $resolvedSlot['photographer_id']
                        )
                        ->where(
                            'studio_room_id',
                            $resolvedSlot['studio_room_id']
                        )
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | Kalau tidak ada schedule legacy, buat baru
                    |--------------------------------------------------------------------------
                    */
                    if ($schedule) {

                        $schedule->update([
                            'status' => 'booked',
                        ]);

                    } else {

                        $schedule = Schedule::create([
                            'branch_id' =>
                                $branch->id,

                            'service_id' =>
                                $service->id,

                            'photographer_id' =>
                                $resolvedSlot[
                                    'photographer_id'
                                ],

                            'studio_room_id' =>
                                $resolvedSlot[
                                    'studio_room_id'
                                ],

                            'schedule_date' =>
                                $resolvedSlot['date'],

                            'start_time' =>
                                $resolvedSlot['start_time'],

                            'end_time' =>
                                $resolvedSlot['end_time'],

                            'status' =>
                                'booked',
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Buat Booking
                    |--------------------------------------------------------------------------
                    */
                    $booking = Booking::create([
                        'booking_code' =>
                            $this->generateBookingCode(),

                        'user_id' =>
                            Auth::id(),

                        'package_id' =>
                            $package->id,

                        'number_of_people' =>
                            (int) $draft[
                                'number_of_people'
                            ],

                        'location_address' =>
                            $service->is_on_location
                            ? ($draft[
                                'location_address'
                            ] ?? null)
                            : null,

                        'location_notes' =>
                            $service->is_on_location
                            ? ($draft[
                                'location_notes'
                            ] ?? null)
                            : null,

                        'notes' =>
                            $draft['notes']
                            ?? null,

                        'total_price' =>
                            $package->price,

                        'status' =>
                            'pending',
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Hubungkan booking dengan schedule
                    |--------------------------------------------------------------------------
                    */
                    $booking
                        ->schedules()
                        ->attach(
                            $schedule->id
                        );

                    return $booking;
                }
            );

        } catch (ValidationException $exception) {

            return redirect()
                ->route('customer.bookings.schedule')
                ->withErrors(
                    $exception->errors()
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking berhasil
        |--------------------------------------------------------------------------
        */
        session()->forget(
            'booking_draft'
        );

        return redirect()
            ->route(
                'customer.payments.create',
                $booking
            )
            ->with(
                'success',
                'Booking berhasil dibuat. Silakan lanjutkan proses pembayaran.'
            );
    }

    public function show(Booking $booking)
    {
        abort_if(
            $booking->user_id !== Auth::id(),
            403
        );

        $booking->load([
            'package.service',
            'payment',
            'schedules.branch',
            'schedules.service',
            'schedules.photographer',
            'schedules.studioRoom',
            'testimonial',
        ]);

        return view(
            'customer.bookings.show',
            compact('booking')
        );
    }

    public function storeTestimonial(Request $request, Booking $booking)
    {
        // Pastikan booking milik pelanggan yang sedang login.
        abort_if($booking->user_id !== Auth::id(), 403);

        // Rating hanya boleh diberikan setelah booking selesai.
        if ($booking->status !== 'completed') {
            return back()->with(
                'error',
                'Rating hanya dapat diberikan setelah booking selesai.'
            );
        }

        // Satu booking hanya boleh memiliki satu ulasan.
        if ($booking->testimonial()->exists()) {
            return back()->with(
                'error',
                'Booking ini sudah memiliki ulasan.'
            );
        }

        // Validasi rating dan komentar.
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'rating.required' => 'Silakan pilih rating bintang.',
            'rating.integer' => 'Rating tidak valid.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'comment.required' => 'Komentar wajib diisi.',
            'comment.min' => 'Komentar minimal 5 karakter.',
            'comment.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        // Simpan sebagai pending agar menunggu persetujuan admin.
        $booking->testimonial()->create([
            'rating' => $validated['rating'],
            'comment' => trim($validated['comment']),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('customer.bookings.show', $booking)
            ->with(
                'success',
                'Rating dan komentar berhasil dikirim dan sedang menunggu persetujuan admin.'
            );
    }

    private function generateBookingCode(): string
    {
        do {

            $code =
                'AURA-'
                . now()->format('Ymd')
                . '-'
                . Str::upper(Str::random(6));

        } while (
            Booking::where(
                'booking_code',
                $code
            )->exists()
        );

        return $code;
    }
}