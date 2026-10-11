<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotographerController extends Controller
{
    /**
     * Menampilkan seluruh fotografer.
     */
    public function index()
    {
        $photographers = Photographer::with('branch')
            ->latest()
            ->get();

        return view(
            'admin.photographers.index',
            compact('photographers')
        );
    }


    /**
     * Form tambah fotografer.
     */
    public function create()
    {
        $branches = Branch::orderBy('name')
            ->get();

        return view(
            'admin.photographers.create',
            compact('branches')
        );
    }


    /**
     * Menyimpan fotografer baru.
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

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,inactive,leave',
            ],
        ]);


        $photo = null;

        if ($request->hasFile('photo')) {
            $photo = $request
                ->file('photo')
                ->store(
                    'photographers',
                    'public'
                );
        }


        Photographer::create([
            'branch_id' =>
                $validated['branch_id'],

            'name' =>
                $validated['name'],

            'photo' =>
                $photo,

            'specialization' =>
                $validated['specialization']
                ?? null,

            'description' =>
                $validated['description']
                ?? null,

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.photographers.index')
            ->with(
                'success',
                'Fotografer berhasil ditambahkan.'
            );
    }


    /**
     * Form edit fotografer.
     */
    public function edit(Photographer $photographer)
    {
        $branches = Branch::orderBy('name')
            ->get();

        return view(
            'admin.photographers.edit',
            compact(
                'photographer',
                'branches'
            )
        );
    }


    /**
     * Memperbarui fotografer.
     */
    public function update(
        Request $request,
        Photographer $photographer
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

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,inactive,leave',
            ],
        ]);


        $photo = $photographer->photo;


        if ($request->hasFile('photo')) {

            $newPhoto = $request
                ->file('photo')
                ->store(
                    'photographers',
                    'public'
                );


            if (
                $photo
                && Storage::disk('public')
                    ->exists($photo)
            ) {
                Storage::disk('public')
                    ->delete($photo);
            }


            $photo = $newPhoto;
        }


        $photographer->update([
            'branch_id' =>
                $validated['branch_id'],

            'name' =>
                $validated['name'],

            'photo' =>
                $photo,

            'specialization' =>
                $validated['specialization']
                ?? null,

            'description' =>
                $validated['description']
                ?? null,

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.photographers.index')
            ->with(
                'success',
                'Fotografer berhasil diperbarui.'
            );
    }


    /**
     * Menghapus fotografer.
     */
    public function destroy(Photographer $photographer)
    {
        /*
        |--------------------------------------------------------------------------
        | CEGAH HAPUS JIKA MASIH DIPAKAI JADWAL
        |--------------------------------------------------------------------------
        */

        if ($photographer->schedules()->exists()) {
            return redirect()
                ->route('admin.photographers.index')
                ->with(
                    'error',
                    'Fotografer tidak dapat dihapus karena masih digunakan pada data jadwal.'
                );
        }


        $photo = $photographer->photo;


        $photographer->delete();


        /*
        |--------------------------------------------------------------------------
        | HAPUS FOTO
        |--------------------------------------------------------------------------
        */

        if (
            $photo
            && Storage::disk('public')
                ->exists($photo)
        ) {
            Storage::disk('public')
                ->delete($photo);
        }


        return redirect()
            ->route('admin.photographers.index')
            ->with(
                'success',
                'Fotografer berhasil dihapus.'
            );
    }
}