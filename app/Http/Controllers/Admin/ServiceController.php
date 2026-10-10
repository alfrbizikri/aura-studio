<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Menampilkan seluruh layanan.
     */
    public function index()
    {
        $services = Service::latest()
            ->get();

        return view(
            'admin.services.index',
            compact('services')
        );
    }


    /**
     * Form tambah layanan.
     */
    public function create()
    {
        return view('admin.services.create');
    }


    /**
     * Menyimpan layanan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'requires_photographer' => [
                'nullable',
                'boolean',
            ],

            'requires_room' => [
                'nullable',
                'boolean',
            ],

            'is_on_location' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;

        $counter = 1;

        while (
            Service::where('slug', $slug)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;

            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request
                ->file('image')
                ->store(
                    'services',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        Service::create([
            'name' => $validated['name'],

            'slug' => $slug,

            'description' =>
                $validated['description']
                ?? null,

            'image' => $image,

            'requires_photographer' =>
                $request->boolean(
                    'requires_photographer'
                ),

            'requires_room' =>
                $request->boolean(
                    'requires_room'
                ),

            'is_on_location' =>
                $request->boolean(
                    'is_on_location'
                ),

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.services.index')
            ->with(
                'success',
                'Layanan berhasil ditambahkan.'
            );
    }


    /**
     * Form edit layanan.
     */
    public function edit(Service $service)
    {
        return view(
            'admin.services.edit',
            compact('service')
        );
    }


    /**
     * Memperbarui layanan.
     */
    public function update(
        Request $request,
        Service $service
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'requires_photographer' => [
                'nullable',
                'boolean',
            ],

            'requires_room' => [
                'nullable',
                'boolean',
            ],

            'is_on_location' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE SLUG
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;

        $counter = 1;

        while (
            Service::where('slug', $slug)
                ->where(
                    'id',
                    '!=',
                    $service->id
                )
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;

            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE IMAGE
        |--------------------------------------------------------------------------
        */

        $image = $service->image;

        if ($request->hasFile('image')) {

            $newImage = $request
                ->file('image')
                ->store(
                    'services',
                    'public'
                );

            if (
                $image
                && Storage::disk('public')
                    ->exists($image)
            ) {
                Storage::disk('public')
                    ->delete($image);
            }

            $image = $newImage;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $service->update([
            'name' => $validated['name'],

            'slug' => $slug,

            'description' =>
                $validated['description']
                ?? null,

            'image' => $image,

            'requires_photographer' =>
                $request->boolean(
                    'requires_photographer'
                ),

            'requires_room' =>
                $request->boolean(
                    'requires_room'
                ),

            'is_on_location' =>
                $request->boolean(
                    'is_on_location'
                ),

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.services.index')
            ->with(
                'success',
                'Layanan berhasil diperbarui.'
            );
    }


    /**
     * Menghapus layanan.
     */
    public function destroy(Service $service)
    {
        /*
        |--------------------------------------------------------------------------
        | CEGAH HAPUS JIKA MASIH DIPAKAI
        |--------------------------------------------------------------------------
        */

        if (
            $service->packages()->exists()
            || $service->schedules()->exists()
            || $service->galleries()->exists()
        ) {
            return redirect()
                ->route('admin.services.index')
                ->with(
                    'error',
                    'Layanan tidak dapat dihapus karena masih digunakan oleh data paket, jadwal, atau galeri.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LEPAS RELASI CABANG
        |--------------------------------------------------------------------------
        */

        $service->branches()->detach();


        /*
        |--------------------------------------------------------------------------
        | SIMPAN PATH GAMBAR SEBELUM DELETE
        |--------------------------------------------------------------------------
        */

        $image = $service->image;


        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA
        |--------------------------------------------------------------------------
        */

        $service->delete();


        /*
        |--------------------------------------------------------------------------
        | HAPUS GAMBAR
        |--------------------------------------------------------------------------
        */

        if (
            $image
            && Storage::disk('public')
                ->exists($image)
        ) {
            Storage::disk('public')
                ->delete($image);
        }


        return redirect()
            ->route('admin.services.index')
            ->with(
                'success',
                'Layanan berhasil dihapus.'
            );
    }
}