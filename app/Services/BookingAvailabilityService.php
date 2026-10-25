<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Package;
use App\Models\Photographer;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\StudioRoom;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BookingAvailabilityService
{
    /**
     * Membuat slot tersedia secara otomatis berdasarkan:
     * - jam operasional cabang
     * - durasi paket
     * - fotografer
     * - studio room
     * - schedule booked / blocked
     */
    public function generateAvailableSlots(
        Branch $branch,
        Service $service,
        Package $package,
        ?int $photographerId = null,
        int $days = 14,
        int $intervalMinutes = 30
    ): Collection {
        $durationMinutes = (int) $package->duration_minutes;

        if ($durationMinutes <= 0) {
            return collect();
        }

        $startDate = today();
        $endDate = today()->copy()->addDays($days - 1);

        /*
        |--------------------------------------------------------------------------
        | Resource aktif
        |--------------------------------------------------------------------------
        */
        $photographers = collect();

        if ($service->requires_photographer) {
            $photographers = Photographer::query()
                ->where('branch_id', $branch->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        $rooms = collect();

        if ($service->requires_room) {
            $rooms = StudioRoom::query()
                ->where('branch_id', $branch->id)
                ->where('service_id', $service->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Jika customer memilih fotografer manual
        |--------------------------------------------------------------------------
        */
        if (
            $service->requires_photographer
            && $photographerId
        ) {
            $photographers = $photographers
                ->where('id', $photographerId)
                ->values();

            if ($photographers->isEmpty()) {
                return collect();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Jadwal yang benar-benar mengurangi availability
        |--------------------------------------------------------------------------
        |
        | AVAILABLE tidak kita gunakan sebagai sumber availability lagi.
        | Availability berasal dari jam buka/tutup cabang.
        |
        */
        $busySchedules = Schedule::query()
            ->where('branch_id', $branch->id)
            ->whereBetween('schedule_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->whereIn('status', [
                'booked',
                'blocked',
            ])
            ->get()
            ->groupBy(function ($schedule) {
                return Carbon::parse(
                    $schedule->schedule_date
                )->format('Y-m-d');
            });

        $slots = collect();

        /*
        |--------------------------------------------------------------------------
        | Generate per hari
        |--------------------------------------------------------------------------
        */
        for ($day = 0; $day < $days; $day++) {
            $date = today()->copy()->addDays($day);

            $opening = Carbon::parse(
                $date->toDateString()
                . ' '
                . $branch->opening_time
            );

            $closing = Carbon::parse(
                $date->toDateString()
                . ' '
                . $branch->closing_time
            );

            /*
             * Jam operasional tidak valid.
             */
            if ($closing->lte($opening)) {
                continue;
            }

            $dayBusySchedules = $busySchedules->get(
                $date->toDateString(),
                collect()
            );

            /*
             * Mulai dari jam buka cabang.
             */
            $cursor = $opening->copy();

            while (true) {
                $slotStart = $cursor->copy();

                $slotEnd = $slotStart
                    ->copy()
                    ->addMinutes($durationMinutes);

                /*
                 * Paket sudah melewati jam tutup.
                 */
                if ($slotEnd->gt($closing)) {
                    break;
                }

                /*
                 * Jangan tampilkan waktu yang sudah lewat.
                 */
                if ($slotStart->lte(now())) {
                    $cursor->addMinutes(
                        $intervalMinutes
                    );

                    continue;
                }

                $resolved = $this->resolveResources(
                    branch: $branch,
                    service: $service,
                    start: $slotStart,
                    end: $slotEnd,
                    busySchedules: $dayBusySchedules,
                    photographers: $photographers,
                    rooms: $rooms
                );

                if ($resolved) {
                    $slots->push([
                        'date' => $date->toDateString(),

                        'date_label' => $date
                            ->locale('id')
                            ->translatedFormat(
                                'l, d F Y'
                            ),

                        'start_time' => $slotStart
                            ->format('H:i'),

                        'end_time' => $slotEnd
                            ->format('H:i'),

                        'photographer_id' =>
                            $resolved['photographer']
                                    ?->id,

                        'photographer_name' =>
                            $resolved['photographer']
                                    ?->name,

                        'studio_room_id' =>
                            $resolved['studio_room']
                                    ?->id,

                        'studio_room_name' =>
                            $resolved['studio_room']
                                    ?->name,
                    ]);
                }

                $cursor->addMinutes(
                    $intervalMinutes
                );
            }
        }

        return $slots;
    }


    /**
     * Mengecek satu slot tertentu.
     *
     * Digunakan kembali ketika:
     * - customer memilih slot
     * - membuka detail
     * - konfirmasi
     * - sebelum booking benar-benar dibuat
     *
     * Jadi kita tidak percaya data dari browser saja.
     */
    public function resolveSlot(
        Branch $branch,
        Service $service,
        Package $package,
        string $date,
        string $startTime,
        ?int $photographerId = null,
        ?int $studioRoomId = null
    ): ?array {
        $durationMinutes = (int) $package->duration_minutes;

        if ($durationMinutes <= 0) {
            return null;
        }

        try {
            $slotDate = Carbon::createFromFormat(
                'Y-m-d',
                $date
            )->startOfDay();

            $start = Carbon::parse(
                $date . ' ' . $startTime
            );
        } catch (\Throwable $exception) {
            return null;
        }

        if ($slotDate->lt(today())) {
            return null;
        }

        if ($start->lte(now())) {
            return null;
        }

        $end = $start
            ->copy()
            ->addMinutes($durationMinutes);

        $opening = Carbon::parse(
            $date
            . ' '
            . $branch->opening_time
        );

        $closing = Carbon::parse(
            $date
            . ' '
            . $branch->closing_time
        );

        /*
         * Harus berada penuh di dalam jam operasional.
         */
        if (
            $start->lt($opening)
            || $end->gt($closing)
        ) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Busy schedule pada tanggal tersebut
        |--------------------------------------------------------------------------
        */
        $busySchedules = Schedule::query()
            ->where(
                'branch_id',
                $branch->id
            )
            ->whereDate(
                'schedule_date',
                $date
            )
            ->whereIn(
                'status',
                [
                    'booked',
                    'blocked',
                ]
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Fotografer
        |--------------------------------------------------------------------------
        */
        $photographers = collect();

        if ($service->requires_photographer) {
            $query = Photographer::query()
                ->where(
                    'branch_id',
                    $branch->id
                )
                ->where(
                    'status',
                    'active'
                );

            /*
             * Kalau ID diberikan, berarti harus memakai
             * fotografer tersebut.
             */
            if ($photographerId) {
                $query->where(
                    'id',
                    $photographerId
                );
            }

            $photographers = $query
                ->orderBy('name')
                ->get();

            if ($photographers->isEmpty()) {
                return null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Studio room
        |--------------------------------------------------------------------------
        */
        $rooms = collect();

        if ($service->requires_room) {
            $query = StudioRoom::query()
                ->where(
                    'branch_id',
                    $branch->id
                )
                ->where(
                    'service_id',
                    $service->id
                )
                ->where(
                    'status',
                    'active'
                );

            if ($studioRoomId) {
                $query->where(
                    'id',
                    $studioRoomId
                );
            }

            $rooms = $query
                ->orderBy('name')
                ->get();

            if ($rooms->isEmpty()) {
                return null;
            }
        }

        $resolved = $this->resolveResources(
            branch: $branch,
            service: $service,
            start: $start,
            end: $end,
            busySchedules: $busySchedules,
            photographers: $photographers,
            rooms: $rooms
        );

        if (!$resolved) {
            return null;
        }

        return [
            'date' => $date,

            'start_time' =>
                $start->format('H:i'),

            'end_time' =>
                $end->format('H:i'),

            'photographer_id' =>
                $resolved['photographer']
                        ?->id,

            'photographer' =>
                $resolved['photographer'],

            'studio_room_id' =>
                $resolved['studio_room']
                        ?->id,

            'studio_room' =>
                $resolved['studio_room'],
        ];
    }

    public function generateAvailableSlotsForDate(
        Branch $branch,
        Service $service,
        Package $package,
        string $date,
        ?int $photographerId = null,
        int $intervalMinutes = 30
    ): Collection {
        try {
            $dateObject = Carbon::createFromFormat(
                'Y-m-d',
                $date
            )->startOfDay();
        } catch (\Throwable $exception) {
            return collect();
        }

        /*
        |--------------------------------------------------------------------------
        | Maksimal booking 1 tahun ke depan
        |--------------------------------------------------------------------------
        */
        $minimumDate = today();
        $maximumDate = today()
            ->copy()
            ->addYear();

        if (
            $dateObject->lt($minimumDate)
            || $dateObject->gt($maximumDate)
        ) {
            return collect();
        }

        $durationMinutes =
            (int) $package->duration_minutes;

        if ($durationMinutes <= 0) {
            return collect();
        }

        $opening = Carbon::parse(
            $date . ' ' . $branch->opening_time
        );

        $closing = Carbon::parse(
            $date . ' ' . $branch->closing_time
        );

        if ($closing->lte($opening)) {
            return collect();
        }

        $busySchedules = Schedule::query()
            ->where('branch_id', $branch->id)
            ->whereDate('schedule_date', $date)
            ->whereIn('status', [
                'booked',
                'blocked',
            ])
            ->get();

        $slots = collect();

        $cursor = $opening->copy();

        while (true) {
            $slotStart =
                $cursor->copy();

            $slotEnd =
                $slotStart
                    ->copy()
                    ->addMinutes($durationMinutes);

            /*
             * Sudah melewati jam tutup.
             */
            if ($slotEnd->gt($closing)) {
                break;
            }

            /*
             * Jangan tampilkan waktu yang sudah lewat.
             */
            if ($slotStart->lte(now())) {
                $cursor->addMinutes(
                    $intervalMinutes
                );

                continue;
            }

            /*
             * Validasi availability lengkap.
             */
            $resolved = $this->resolveSlot(
                branch: $branch,
                service: $service,
                package: $package,
                date: $date,
                startTime: $slotStart->format('H:i'),
                photographerId: $photographerId
            );

            /*
|--------------------------------------------------------------------------
| Tentukan status slot
|--------------------------------------------------------------------------
|
| Slot tidak lagi dihilangkan ketika tidak tersedia.
| Slot tetap dikirim ke halaman reservasi agar customer dapat
| melihat jam yang sudah dibooking / diblokir.
|
*/

            $isBlocked = false;
            $isBooked = false;

            if (!$resolved) {
                foreach ($busySchedules as $schedule) {
                    if (
                        !$this->overlaps(
                            $slotStart,
                            $slotEnd,
                            $schedule
                        )
                    ) {
                        continue;
                    }

                    /*
                     * Block seluruh service.
                     */
                    if (
                        $schedule->status === 'blocked'
                        && $schedule->service_id === $service->id
                        && $schedule->photographer_id === null
                        && $schedule->studio_room_id === null
                    ) {
                        $isBlocked = true;
                        break;
                    }

                    /*
                     * Jika customer memilih fotografer tertentu,
                     * cek apakah fotografer tersebut diblokir.
                     */
                    if (
                        $schedule->status === 'blocked'
                        && $photographerId
                        && $schedule->photographer_id === $photographerId
                    ) {
                        $isBlocked = true;
                        break;
                    }

                    /*
                     * Ada booking yang bentrok.
                     */
                    if ($schedule->status === 'booked') {
                        $isBooked = true;
                    }
                }
            }

            $status = 'available';
            $statusLabel = 'Tersedia';

            if (!$resolved) {
                if ($isBlocked) {
                    $status = 'blocked';
                    $statusLabel = 'Tidak Tersedia';
                } elseif ($isBooked) {
                    $status = 'booked';
                    $statusLabel = 'Sudah Dibooking';
                } else {
                    $status = 'unavailable';
                    $statusLabel = 'Tidak Tersedia';
                }
            }

            $slots->push([
                'date' => $date,

                'date_label' => $dateObject
                    ->locale('id')
                    ->translatedFormat(
                        'l, d F Y'
                    ),

                'start_time' =>
                    $slotStart->format('H:i'),

                'end_time' =>
                    $slotEnd->format('H:i'),

                /*
                 * Status availability untuk frontend.
                 */
                'available' => (bool) $resolved,

                'status' => $status,

                'status_label' => $statusLabel,

                /*
                 * Resource hanya ada jika slot berhasil di-resolve.
                 */
                'photographer_id' =>
                    $resolved['photographer_id']
                    ?? null,

                'photographer_name' =>
                    $resolved['photographer']
                            ?->name
                    ?? null,

                'studio_room_id' =>
                    $resolved['studio_room_id']
                    ?? null,

                'studio_room_name' =>
                    $resolved['studio_room']
                            ?->name
                    ?? null,
            ]);

            $cursor->addMinutes(
                $intervalMinutes
            );
        }

        return $slots;
    }


    /**
     * Menentukan fotografer dan studio room
     * yang benar-benar tersedia.
     */
    private function resolveResources(
        Branch $branch,
        Service $service,
        Carbon $start,
        Carbon $end,
        Collection $busySchedules,
        Collection $photographers,
        Collection $rooms
    ): ?array {
        /*
        |--------------------------------------------------------------------------
        | Block seluruh layanan
        |--------------------------------------------------------------------------
        |
        | Blocked schedule tanpa photographer dan room
        | dianggap menutup layanan tersebut pada waktu itu.
        |
        */
        $serviceBlocked = $busySchedules
            ->contains(function ($schedule) use ($service, $start, $end) {
                if (
                    $schedule->status !== 'blocked'
                    || $schedule->service_id
                    !== $service->id
                    || $schedule->photographer_id
                    !== null
                    || $schedule->studio_room_id
                    !== null
                ) {
                    return false;
                }

                return $this->overlaps(
                    $start,
                    $end,
                    $schedule
                );
            });

        if ($serviceBlocked) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Cari fotografer tersedia
        |--------------------------------------------------------------------------
        */
        $selectedPhotographer = null;

        if ($service->requires_photographer) {
            $selectedPhotographer = $photographers
                ->first(function ($photographer) use ($busySchedules, $start, $end) {
                    return !$busySchedules
                        ->contains(function ($schedule) use ($photographer, $start, $end) {
                            if (
                                $schedule->photographer_id
                                !== $photographer->id
                            ) {
                                return false;
                            }

                            return $this->overlaps(
                                $start,
                                $end,
                                $schedule
                            );
                        });
                });

            if (!$selectedPhotographer) {
                return null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cari studio room tersedia
        |--------------------------------------------------------------------------
        */
        $selectedRoom = null;

        if ($service->requires_room) {
            $selectedRoom = $rooms
                ->first(function ($room) use ($busySchedules, $start, $end) {
                    return !$busySchedules
                        ->contains(function ($schedule) use ($room, $start, $end) {
                            if (
                                $schedule->studio_room_id
                                !== $room->id
                            ) {
                                return false;
                            }

                            return $this->overlaps(
                                $start,
                                $end,
                                $schedule
                            );
                        });
                });

            if (!$selectedRoom) {
                return null;
            }
        }

        return [
            'photographer' =>
                $selectedPhotographer,

            'studio_room' =>
                $selectedRoom,
        ];
    }


    /**
     * Apakah dua rentang waktu saling bertabrakan?
     *
     * Contoh:
     * Existing : 10:00 - 11:30
     * Candidate: 11:00 - 12:30
     *
     * => bentrok.
     */
    private function overlaps(
        Carbon $start,
        Carbon $end,
        Schedule $schedule
    ): bool {
        $date = Carbon::parse(
            $schedule->schedule_date
        )->format('Y-m-d');

        $existingStart = Carbon::parse(
            $date
            . ' '
            . $schedule->start_time
        );

        $existingEnd = Carbon::parse(
            $date
            . ' '
            . $schedule->end_time
        );

        return $start->lt($existingEnd)
            && $end->gt($existingStart);
    }
}