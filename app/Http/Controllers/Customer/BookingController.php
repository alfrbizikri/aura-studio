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

    public function schedule()
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
                ->with('error', 'Silakan mulai proses booking kembali.');
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

        $photographer = null;

        if (!empty($draft['photographer_id'])) {
            $photographer = Photographer::query()
                ->where('id', $draft['photographer_id'])
                ->where('branch_id', $branch->id)
                ->where('status', 'active')
                ->firstOrFail();
        }

        $schedules = Schedule::with([
            'photographer',
            'studioRoom',
        ])
            ->where('branch_id', $branch->id)
            ->where('service_id', $service->id)
            ->where('status', 'available')

            ->when(
                $photographer,
                function ($query) use ($photographer) {
                    $query->where(
                        'photographer_id',
                        $photographer->id
                    );
                }
            )

            ->when(
                $service->requires_photographer,
                function ($query) {
                    $query
                        ->whereNotNull('photographer_id')
                        ->whereHas('photographer', function ($photographerQuery) {
                            $photographerQuery
                                ->where('status', 'active');
                        });
                }
            )

            ->when(
                $service->requires_room,
                function ($query) {
                    $query
                        ->whereNotNull('studio_room_id')
                        ->whereHas('studioRoom', function ($roomQuery) {
                            $roomQuery
                                ->where('status', 'active');
                        });
                }
            )

            ->where(function ($query) {
                $query
                    ->whereDate('schedule_date', '>', today())
                    ->orWhere(function ($todayQuery) {
                        $todayQuery
                            ->whereDate('schedule_date', today())
                            ->whereTime(
                                'start_time',
                                '>',
                                now()->format('H:i:s')
                            );
                    });
            })

            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get()

            /*
             * Pastikan durasi Schedule cukup
             * untuk durasi Package.
             */
            ->filter(function ($schedule) use ($package) {

                $start = Carbon::createFromFormat(
                    'H:i:s',
                    $schedule->start_time
                );

                $end = Carbon::createFromFormat(
                    'H:i:s',
                    $schedule->end_time
                );

                return $start->diffInMinutes($end)
                    >= $package->duration_minutes;
            })

            ->values();

        $scheduleGroups = $schedules->groupBy(
            fn($schedule) =>
                $schedule->schedule_date->format('Y-m-d')
        );

        $selectedScheduleId =
            old('schedule_id')
            ?? ($draft['schedule_id'] ?? null);

        return view(
            'customer.bookings.schedule',
            compact(
                'service',
                'branch',
                'package',
                'photographer',
                'schedules',
                'scheduleGroups',
                'selectedScheduleId'
            )
        );
    }


    public function storeSchedule(Request $request)
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
                ->with('error', 'Data booking tidak ditemukan.');
        }

        $validated = $request->validate([
            'schedule_id' => [
                'required',
                'integer',
                'exists:schedules,id',
            ],
        ]);

        $service = Service::query()
            ->where('id', $draft['service_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $package = Package::query()
            ->where('id', $draft['package_id'])
            ->where('service_id', $service->id)
            ->where('is_active', true)
            ->firstOrFail();

        $schedule = Schedule::with([
            'branch',
            'photographer',
            'studioRoom',
        ])
            ->where('id', $validated['schedule_id'])
            ->where('branch_id', $draft['branch_id'])
            ->where('service_id', $draft['service_id'])
            ->where('status', 'available')
            ->first();

        if (!$schedule) {
            return back()
                ->withInput()
                ->withErrors([
                    'schedule_id' =>
                        'Jadwal tersebut sudah tidak tersedia.',
                ]);
        }

        /*
         * Jika customer memilih fotografer manual,
         * jadwal wajib memakai fotografer tersebut.
         */
        if (
            !empty($draft['photographer_id'])
            && (int) $schedule->photographer_id
            !== (int) $draft['photographer_id']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'schedule_id' =>
                        'Jadwal tidak sesuai dengan fotografer yang dipilih.',
                ]);
        }

        if (
            $schedule->schedule_date->isBefore(today())
            || (
                $schedule->schedule_date->isToday()
                && $schedule->start_time
                <= now()->format('H:i:s')
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'schedule_id' =>
                        'Jadwal yang dipilih sudah lewat.',
                ]);
        }

        if (
            !$schedule->branch
            || $schedule->branch->status !== 'active'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'schedule_id' =>
                        'Cabang pada jadwal ini sedang tidak aktif.',
                ]);
        }

        if ($service->requires_photographer) {
            if (
                !$schedule->photographer
                || $schedule->photographer->status !== 'active'
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'schedule_id' =>
                            'Fotografer pada jadwal ini tidak tersedia.',
                    ]);
            }
        }

        if ($service->requires_room) {
            if (
                !$schedule->studioRoom
                || $schedule->studioRoom->status !== 'active'
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'schedule_id' =>
                            'Studio room pada jadwal ini tidak tersedia.',
                    ]);
            }
        }

        $start = Carbon::createFromFormat(
            'H:i:s',
            $schedule->start_time
        );

        $end = Carbon::createFromFormat(
            'H:i:s',
            $schedule->end_time
        );

        if (
            $start->diffInMinutes($end)
            < $package->duration_minutes
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'schedule_id' =>
                        'Durasi jadwal tidak mencukupi untuk paket ini.',
                ]);
        }

        $draft['schedule_id'] = $schedule->id;

        /*
         * Kalau fotografer dipilih otomatis,
         * kita simpan fotografer hasil Schedule juga.
         * photographer_mode tetap "auto".
         */
        $draft['assigned_photographer_id'] =
            $schedule->photographer_id;

        session([
            'booking_draft' => $draft,
        ]);

        return redirect()
            ->route('customer.bookings.detail');
    }

    public function detail()
    {
        $draft = session('booking_draft');

        if (
            !$draft
            || empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_id'])
        ) {
            return redirect()
                ->route('packages.index')
                ->with(
                    'error',
                    'Data booking belum lengkap. Silakan mulai kembali.'
                );
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

        $schedule = Schedule::with([
            'photographer',
            'studioRoom',
        ])
            ->where('id', $draft['schedule_id'])
            ->where('branch_id', $branch->id)
            ->where('service_id', $service->id)
            ->where('status', 'available')
            ->first();

        if (!$schedule) {
            $draft['schedule_id'] = null;

            session([
                'booking_draft' => $draft,
            ]);

            return redirect()
                ->route('customer.bookings.schedule')
                ->withErrors([
                    'schedule_id' =>
                        'Jadwal yang sebelumnya dipilih sudah tidak tersedia.',
                ]);
        }

        $customer = Auth::user();

        return view(
            'customer.bookings.detail',
            compact(
                'service',
                'branch',
                'package',
                'schedule',
                'customer',
                'draft'
            )
        );
    }


    public function storeDetail(Request $request)
    {
        $draft = session('booking_draft');

        if (
            !$draft
            || empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_id'])
        ) {
            return redirect()
                ->route('packages.index')
                ->with(
                    'error',
                    'Data booking tidak ditemukan. Silakan mulai kembali.'
                );
        }

        $service = Service::query()
            ->where('id', $draft['service_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $package = Package::query()
            ->where('id', $draft['package_id'])
            ->where('service_id', $service->id)
            ->where('is_active', true)
            ->firstOrFail();

        $schedule = Schedule::query()
            ->where('id', $draft['schedule_id'])
            ->where('branch_id', $draft['branch_id'])
            ->where('service_id', $service->id)
            ->where('status', 'available')
            ->first();

        if (!$schedule) {
            return redirect()
                ->route('customer.bookings.schedule')
                ->withErrors([
                    'schedule_id' =>
                        'Jadwal yang dipilih sudah tidak tersedia.',
                ]);
        }

        $maxPeople = $package->max_people ?: 255;

        $validated = $request->validate([
            'number_of_people' => [
                'required',
                'integer',
                'min:1',
                'max:' . $maxPeople,
            ],

            'location_address' => [
                $service->is_on_location
                ? 'required'
                : 'nullable',
                'string',
                'max:1000',
            ],

            'location_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'number_of_people.required' =>
                'Jumlah peserta wajib diisi.',

            'number_of_people.min' =>
                'Jumlah peserta minimal 1 orang.',

            'number_of_people.max' =>
                'Jumlah peserta melebihi kapasitas paket.',

            'location_address.required' =>
                'Alamat lokasi wajib diisi untuk layanan on-location.',
        ]);

        $draft['number_of_people'] =
            $validated['number_of_people'];

        $draft['location_address'] =
            $service->is_on_location
            ? $validated['location_address']
            : null;

        $draft['location_notes'] =
            $service->is_on_location
            ? ($validated['location_notes'] ?? null)
            : null;

        $draft['notes'] =
            $validated['notes'] ?? null;

        session([
            'booking_draft' => $draft,
        ]);

        return redirect()
            ->route('customer.bookings.confirmation');
    }

    public function confirmation()
    {
        $draft = session('booking_draft');

        if (
            !$draft
            || empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_id'])
            || empty($draft['number_of_people'])
        ) {
            return redirect()
                ->route('packages.index')
                ->with(
                    'error',
                    'Data booking belum lengkap. Silakan mulai kembali.'
                );
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

        $schedule = Schedule::with([
            'photographer',
            'studioRoom',
        ])
            ->where('id', $draft['schedule_id'])
            ->where('branch_id', $branch->id)
            ->where('service_id', $service->id)
            ->where('status', 'available')
            ->first();

        if (!$schedule) {
            $draft['schedule_id'] = null;

            session([
                'booking_draft' => $draft,
            ]);

            return redirect()
                ->route('customer.bookings.schedule')
                ->withErrors([
                    'schedule_id' =>
                        'Jadwal yang dipilih sudah tidak tersedia.',
                ]);
        }

        $customer = Auth::user();

        return view(
            'customer.bookings.confirmation',
            compact(
                'service',
                'branch',
                'package',
                'schedule',
                'customer',
                'draft'
            )
        );
    }


    public function store(Request $request)
    {
        $request->validate([
            'confirmation' => [
                'accepted',
            ],
        ], [
            'confirmation.accepted' =>
                'Silakan setujui detail booking sebelum melanjutkan.',
        ]);

        $draft = session('booking_draft');

        if (
            !$draft
            || empty($draft['service_id'])
            || empty($draft['branch_id'])
            || empty($draft['package_id'])
            || empty($draft['schedule_id'])
            || empty($draft['number_of_people'])
        ) {
            return redirect()
                ->route('packages.index')
                ->with(
                    'error',
                    'Data booking tidak lengkap. Silakan mulai kembali.'
                );
        }

        $service = Service::query()
            ->where('id', $draft['service_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $package = Package::query()
            ->where('id', $draft['package_id'])
            ->where('service_id', $service->id)
            ->where('is_active', true)
            ->firstOrFail();

        if (
            $package->max_people
            && $draft['number_of_people'] > $package->max_people
        ) {
            return redirect()
                ->route('customer.bookings.detail')
                ->withErrors([
                    'number_of_people' =>
                        'Jumlah peserta melebihi kapasitas paket.',
                ]);
        }

        try {

            $booking = DB::transaction(function () use ($draft, $service, $package) {

                /*
                |--------------------------------------------------------------------------
                | Lock schedule
                |--------------------------------------------------------------------------
                |
                | Mencegah dua customer mengambil slot yang sama
                | pada waktu hampir bersamaan.
                |
                */
                $schedule = Schedule::query()
                    ->where('id', $draft['schedule_id'])
                    ->where('branch_id', $draft['branch_id'])
                    ->where('service_id', $service->id)
                    ->lockForUpdate()
                    ->first();

                if (!$schedule) {
                    throw ValidationException::withMessages([
                        'schedule_id' =>
                            'Jadwal booking tidak ditemukan.',
                    ]);
                }

                if ($schedule->status !== 'available') {
                    throw ValidationException::withMessages([
                        'schedule_id' =>
                            'Jadwal ini baru saja dipesan oleh customer lain. Silakan pilih jadwal lain.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Pastikan jadwal belum lewat
                |--------------------------------------------------------------------------
                */
                if (
                    $schedule->schedule_date->isBefore(today())
                    || (
                        $schedule->schedule_date->isToday()
                        && $schedule->start_time
                        <= now()->format('H:i:s')
                    )
                ) {
                    throw ValidationException::withMessages([
                        'schedule_id' =>
                            'Jadwal yang dipilih sudah lewat.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Load relasi setelah row dikunci
                |--------------------------------------------------------------------------
                */
                $schedule->load([
                    'branch',
                    'photographer',
                    'studioRoom',
                ]);

                if (
                    !$schedule->branch
                    || $schedule->branch->status !== 'active'
                ) {
                    throw ValidationException::withMessages([
                        'schedule_id' =>
                            'Cabang untuk jadwal ini sedang tidak aktif.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Fotografer
                |--------------------------------------------------------------------------
                */
                if ($service->requires_photographer) {

                    if (
                        !$schedule->photographer
                        || $schedule->photographer->status !== 'active'
                    ) {
                        throw ValidationException::withMessages([
                            'schedule_id' =>
                                'Fotografer pada jadwal ini tidak tersedia.',
                        ]);
                    }

                    if (
                        !empty($draft['photographer_id'])
                        && (int) $schedule->photographer_id
                        !== (int) $draft['photographer_id']
                    ) {
                        throw ValidationException::withMessages([
                            'schedule_id' =>
                                'Jadwal tidak sesuai dengan fotografer yang dipilih.',
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Studio room
                |--------------------------------------------------------------------------
                */
                if ($service->requires_room) {

                    if (
                        !$schedule->studioRoom
                        || $schedule->studioRoom->status !== 'active'
                    ) {
                        throw ValidationException::withMessages([
                            'schedule_id' =>
                                'Studio room pada jadwal ini tidak tersedia.',
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Validasi lokasi
                |--------------------------------------------------------------------------
                */
                if (
                    $service->is_on_location
                    && empty($draft['location_address'])
                ) {
                    throw ValidationException::withMessages([
                        'location_address' =>
                            'Alamat lokasi pemotretan wajib diisi.',
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
                        $draft['number_of_people'],

                    'location_address' =>
                        $service->is_on_location
                        ? ($draft['location_address'] ?? null)
                        : null,

                    'location_notes' =>
                        $service->is_on_location
                        ? ($draft['location_notes'] ?? null)
                        : null,

                    'notes' =>
                        $draft['notes'] ?? null,

                    'total_price' =>
                        $package->price,

                    'status' =>
                        'pending',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Hubungkan schedule
                |--------------------------------------------------------------------------
                */
                $booking->schedules()
                    ->attach($schedule->id);

                /*
                |--------------------------------------------------------------------------
                | Slot tidak boleh muncul lagi
                |--------------------------------------------------------------------------
                */
                $schedule->update([
                    'status' => 'booked',
                ]);

                return $booking;
            });

        } catch (ValidationException $exception) {

            return redirect()
                ->route('customer.bookings.schedule')
                ->withErrors(
                    $exception->errors()
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus draft
        |--------------------------------------------------------------------------
        */
        session()->forget('booking_draft');

        return redirect()
            ->route('customer.payments.create', $booking)
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
        ]);

        return view(
            'customer.bookings.show',
            compact('booking')
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