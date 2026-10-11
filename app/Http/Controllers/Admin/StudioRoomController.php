<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\StudioRoom;
use Illuminate\Http\Request;

class StudioRoomController extends Controller
{
    /**
     * Menampilkan seluruh studio room.
     */
    public function index()
    {
        $studioRooms = StudioRoom::with('branch')
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
        $branches = Branch::orderBy('name')
            ->get();

        return view(
            'admin.studio-rooms.create',
            compact('branches')
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


        StudioRoom::create([
            'branch_id' => $validated['branch_id'],

            'name' => $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'status' => $validated['status'],
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
        $branches = Branch::orderBy('name')
            ->get();

        return view(
            'admin.studio-rooms.edit',
            compact(
                'studioRoom',
                'branches'
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


        $studioRoom->update([
            'branch_id' => $validated['branch_id'],

            'name' => $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'status' => $validated['status'],
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