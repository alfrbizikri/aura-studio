<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Service;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('services')
            ->orderBy('name')
            ->get();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $services = Service::orderBy('name')->get();

        return view('admin.branches.create', compact('services'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'required',
                'string',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'opening_time' => [
                'required',
                'date_format:H:i',
            ],

            'closing_time' => [
                'required',
                'date_format:H:i',
                'after:opening_time',
            ],

            'maps_url' => [
                'nullable',
                'url',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'services' => [
                'nullable',
                'array',
            ],

            'services.*' => [
                'integer',
                'distinct',
                'exists:services,id',
            ],
        ]);

        // Ambil daftar layanan yang dicentang.
        $serviceIds = $validated['services'] ?? [];

        // Kolom services bukan kolom tabel branches.
        unset($validated['services']);

        // Simpan data cabang.
        $branch = Branch::create($validated);

        // Simpan relasi layanan pada cabang.
        $branch->services()->sync(
            array_fill_keys($serviceIds, [
                'status' => 'active',
            ])
        );

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Cabang berhasil ditambahkan beserta layanan yang dipilih.'
            );
    }


    public function edit(Branch $branch)
    {
        $branch->load('services');

        $services = Service::orderBy('name')->get();

        return view(
            'admin.branches.edit',
            compact('branch', 'services')
        );
    }


    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'required',
                'string',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'opening_time' => [
                'required',
                'date_format:H:i',
            ],

            'closing_time' => [
                'required',
                'date_format:H:i',
                'after:opening_time',
            ],

            'maps_url' => [
                'nullable',
                'url',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'services' => [
                'nullable',
                'array',
            ],

            'services.*' => [
                'integer',
                'distinct',
                'exists:services,id',
            ],
        ]);

        // Ambil layanan yang dicentang.
        $serviceIds = $validated['services'] ?? [];

        // Pisahkan relasi dari data utama cabang.
        unset($validated['services']);

        // Perbarui informasi cabang.
        $branch->update($validated);

        // Sinkronkan layanan sesuai pilihan terbaru.
        $branch->services()->sync(
            array_fill_keys($serviceIds, [
                'status' => 'active',
            ])
        );

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Data cabang dan layanan berhasil diperbarui.'
            );
    }


    public function destroy(Branch $branch)
    {
        if (
            $branch->photographers()->exists()
            || $branch->studioRooms()->exists()
            || $branch->schedules()->exists()
        ) {
            return redirect()
                ->route('admin.branches.index')
                ->with(
                    'error',
                    'Cabang tidak dapat dihapus karena masih memiliki data terkait. Ubah status menjadi inactive jika cabang sudah tidak digunakan.'
                );
        }

        $branch->services()->detach();

        $branch->delete();

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Cabang berhasil dihapus.'
            );
    }
}