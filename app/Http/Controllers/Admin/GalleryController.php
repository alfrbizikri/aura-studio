<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('service')
            ->latest()
            ->get();

        return view(
            'admin.galleries.index',
            compact('galleries')
        );
    }


    public function create()
    {
        $services = Service::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.galleries.create',
            compact('services')
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => [
                'required',
                'exists:services,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'required',
                'in:published,hidden',
            ],
        ]);


        $validated['image'] = $request
            ->file('image')
            ->store('galleries', 'public');


        Gallery::create($validated);


        return redirect()
            ->route('admin.galleries.index')
            ->with(
                'success',
                'Galeri berhasil ditambahkan.'
            );
    }


    public function edit(Gallery $gallery)
    {
        $services = Service::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.galleries.edit',
            compact('gallery', 'services')
        );
    }


    public function update(
        Request $request,
        Gallery $gallery
    ) {
        $validated = $request->validate([
            'service_id' => [
                'required',
                'exists:services,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'required',
                'in:published,hidden',
            ],
        ]);


        if ($request->hasFile('image')) {

            if (
                $gallery->image &&
                Storage::disk('public')
                    ->exists($gallery->image)
            ) {
                Storage::disk('public')
                    ->delete($gallery->image);
            }


            $validated['image'] = $request
                ->file('image')
                ->store('galleries', 'public');
        }


        $gallery->update($validated);


        return redirect()
            ->route('admin.galleries.index')
            ->with(
                'success',
                'Galeri berhasil diperbarui.'
            );
    }


    public function destroy(Gallery $gallery)
    {
        if (
            $gallery->image &&
            Storage::disk('public')
                ->exists($gallery->image)
        ) {
            Storage::disk('public')
                ->delete($gallery->image);
        }


        $gallery->delete();


        return redirect()
            ->route('admin.galleries.index')
            ->with(
                'success',
                'Galeri berhasil dihapus.'
            );
    }
}