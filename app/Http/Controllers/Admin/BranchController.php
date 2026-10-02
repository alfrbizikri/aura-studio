<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name')->get();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
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
        ]);

        Branch::create($validated);

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Cabang berhasil ditambahkan.'
            );
    }

    public function edit(Branch $branch)
    {
        return view(
            'admin.branches.edit',
            compact('branch')
        );
    }

    public function update(
        Request $request,
        Branch $branch
    ) {
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
        ]);

        $branch->update($validated);

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Data cabang berhasil diperbarui.'
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