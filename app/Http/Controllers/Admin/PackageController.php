<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Menampilkan seluruh paket.
     */
    public function index()
    {
        $packages = Package::with('service')
            ->latest()
            ->get();

        return view(
            'admin.packages.index',
            compact('packages')
        );
    }


    /**
     * Form tambah paket.
     */
    public function create()
    {
        $services = Service::orderBy('name')
            ->get();

        return view(
            'admin.packages.create',
            compact('services')
        );
    }


    /**
     * Menyimpan paket baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],

            'max_people' => [
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],

            'photo_count' => [
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],

            'package_includes' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);


        Package::create([
            'service_id' =>
                $validated['service_id'],

            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'price' =>
                $validated['price'],

            'duration_minutes' =>
                $validated['duration_minutes'],

            'max_people' =>
                $validated['max_people']
                ?? null,

            'photo_count' =>
                $validated['photo_count']
                ?? null,

            'package_includes' =>
                $validated['package_includes']
                ?? null,

            'is_active' =>
                $request->boolean('is_active'),
        ]);


        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                'Paket berhasil ditambahkan.'
            );
    }


    /**
     * Form edit paket.
     */
    public function edit(Package $package)
    {
        $services = Service::orderBy('name')
            ->get();

        return view(
            'admin.packages.edit',
            compact(
                'package',
                'services'
            )
        );
    }


    /**
     * Memperbarui paket.
     */
    public function update(
        Request $request,
        Package $package
    ) {
        $validated = $request->validate([
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

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],

            'max_people' => [
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],

            'photo_count' => [
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],

            'package_includes' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);


        $package->update([
            'service_id' =>
                $validated['service_id'],

            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'price' =>
                $validated['price'],

            'duration_minutes' =>
                $validated['duration_minutes'],

            'max_people' =>
                $validated['max_people']
                ?? null,

            'photo_count' =>
                $validated['photo_count']
                ?? null,

            'package_includes' =>
                $validated['package_includes']
                ?? null,

            'is_active' =>
                $request->boolean('is_active'),
        ]);


        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                'Paket berhasil diperbarui.'
            );
    }


    /**
     * Menghapus paket.
     */
    public function destroy(Package $package)
    {
        /*
        |--------------------------------------------------------------------------
        | CEGAH HAPUS JIKA SUDAH DIPAKAI BOOKING
        |--------------------------------------------------------------------------
        */

        if ($package->bookings()->exists()) {
            return redirect()
                ->route('admin.packages.index')
                ->with(
                    'error',
                    'Paket tidak dapat dihapus karena sudah digunakan pada data booking.'
                );
        }


        $package->delete();


        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                'Paket berhasil dihapus.'
            );
    }
}