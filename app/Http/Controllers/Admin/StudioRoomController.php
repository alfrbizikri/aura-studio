<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Service;
use App\Models\StudioRoom;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudioRoomController extends Controller
{
    /**
     * Menampilkan seluruh studio room.
     */
    public function index()
    {
        $studioRooms = StudioRoom::with([
            'branch',
            'service',
        ])
            ->latest()
            ->get();

        return view(
            'admin.studio-rooms.index',
            compact('studioRooms')
        );
    }


    /**
     * Form tambah studio room.
     */
    public function create()
    {
        $branches = Branch::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        /*
         * Hanya layanan yang memang
         * membutuhkan studio room.
         */
        $services = Service::query()
            ->where('status', 'active')
            ->where('requires_room', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.studio-rooms.create',
            compact(
                'branches',
                'services'
            )
        );
    }


    /**
     * Menyimpan studio room baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],

            'service_id' => [
                'required',
                'exists:services,id',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,maintenance,inactive',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validasi layanan
        |--------------------------------------------------------------------------
        |
        | Room hanya boleh dikaitkan dengan layanan:
        | - aktif
        | - requires_room = true
        | - tersedia pada cabang tersebut
        |
        */
        $service = Service::query()
            ->where('id', $validated['service_id'])
            ->where('status', 'active')
            ->where('requires_room', true)
            ->first();

        if (!$service) {
            throw ValidationException::withMessages([
                'service_id' =>
                    'Layanan tersebut tidak dapat menggunakan studio room.',
            ]);
        }


        $branchOffersService = Branch::query()
            ->where('id', $validated['branch_id'])
            ->where('status', 'active')
            ->whereHas(
                'services',
                function ($query) use ($validated) {
                    $query
                        ->where(
                            'services.id',
                            $validated['service_id']
                        )
                        ->where(
                            'services.status',
                            'active'
                        )
                        ->where(
                            'branch_service.status',
                            'active'
                        );
                }
            )
            ->exists();


        if (!$branchOffersService) {
            throw ValidationException::withMessages([
                'service_id' =>
                    'Layanan tersebut tidak tersedia pada cabang yang dipilih.',
            ]);
        }


        StudioRoom::create([
            'branch_id' =>
                $validated['branch_id'],

            'service_id' =>
                $validated['service_id'],

            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.studio-rooms.index')
            ->with(
                'success',
                'Studio room berhasil ditambahkan.'
            );
    }


    /**
     * Form edit studio room.
     */
    public function edit(StudioRoom $studioRoom)
    {
        $branches = Branch::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $services = Service::query()
            ->where('status', 'active')
            ->where('requires_room', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.studio-rooms.edit',
            compact(
                'studioRoom',
                'branches',
                'services'
            )
        );
    }


    /**
     * Memperbarui studio room.
     */
    public function update(
        Request $request,
        StudioRoom $studioRoom
    ) {
        $validated = $request->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],

            'service_id' => [
                'required',
                'exists:services,id',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,maintenance,inactive',
            ],
        ]);


        $service = Service::query()
            ->where('id', $validated['service_id'])
            ->where('status', 'active')
            ->where('requires_room', true)
            ->first();


        if (!$service) {
            throw ValidationException::withMessages([
                'service_id' =>
                    'Layanan tersebut tidak dapat menggunakan studio room.',
            ]);
        }


        $branchOffersService = Branch::query()
            ->where('id', $validated['branch_id'])
            ->where('status', 'active')
            ->whereHas(
                'services',
                function ($query) use ($validated) {
                    $query
                        ->where(
                            'services.id',
                            $validated['service_id']
                        )
                        ->where(
                            'services.status',
                            'active'
                        )
                        ->where(
                            'branch_service.status',
                            'active'
                        );
                }
            )
            ->exists();


        if (!$branchOffersService) {
            throw ValidationException::withMessages([
                'service_id' =>
                    'Layanan tersebut tidak tersedia pada cabang yang dipilih.',
            ]);
        }


        $studioRoom->update([
            'branch_id' =>
                $validated['branch_id'],

            'service_id' =>
                $validated['service_id'],

            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.studio-rooms.index')
            ->with(
                'success',
                'Studio room berhasil diperbarui.'
            );
    }


    /**
     * Menghapus studio room.
     */
    public function destroy(StudioRoom $studioRoom)
    {
        if ($studioRoom->schedules()->exists()) {
            return redirect()
                ->route('admin.studio-rooms.index')
                ->with(
                    'error',
                    'Studio room tidak dapat dihapus karena masih digunakan pada data jadwal.'
                );
        }


        $studioRoom->delete();


        return redirect()
            ->route('admin.studio-rooms.index')
            ->with(
                'success',
                'Studio room berhasil dihapus.'
            );
    }
}