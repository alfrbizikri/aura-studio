@extends('layouts.admin')

@section('title', 'Kelola Galeri - Admin Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-gallery.css') }}"
    >
@endpush


@section('content')


<div class="admin-page-header">

    <div>

        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Kelola Galeri
        </h1>

        <p>
            Tambah, ubah, publikasikan, atau hapus
            foto galeri Aura Studio.
        </p>

    </div>


    <a
        href="{{ route('admin.galleries.create') }}"
        class="admin-btn-primary"
    >
        <i class="bi bi-plus-lg"></i>

        Tambah Galeri
    </a>

</div>



{{-- PESAN BERHASIL --}}
@if (session('success'))

    <div class="admin-alert success">

        <i class="bi bi-check-circle"></i>

        {{ session('success') }}

    </div>

@endif



{{-- STATISTIK --}}
<div class="admin-stats">

    <div class="admin-stat-card">

        <span>
            Total Galeri
        </span>

        <strong>
            {{ $galleries->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Published
        </span>

        <strong>
            {{ $galleries->where('status', 'published')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Hidden
        </span>

        <strong>
            {{ $galleries->where('status', 'hidden')->count() }}
        </strong>

    </div>

</div>



{{-- TABLE --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Galeri
        </h2>

        <p>
            Semua foto galeri yang tersimpan dalam sistem.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Foto</th>

                    <th>Judul</th>

                    <th>Layanan</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($galleries as $gallery)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>


                    {{-- FOTO --}}
                    <td>

                        <div class="admin-gallery-thumb">

                            @if (
                                $gallery->image &&
                                \Illuminate\Support\Facades\Storage::disk('public')
                                    ->exists($gallery->image)
                            )

                                <img
                                    src="{{ asset('storage/' . $gallery->image) }}"
                                    alt="{{ $gallery->title }}"
                                >

                            @else

                                <div class="admin-gallery-placeholder">

                                    <i class="bi bi-image"></i>

                                </div>

                            @endif

                        </div>

                    </td>


                    {{-- JUDUL --}}
                    <td>

                        <strong>
                            {{ $gallery->title }}
                        </strong>


                        @if ($gallery->description)

                            <div class="admin-gallery-description">

                                {{ \Illuminate\Support\Str::limit(
                                    $gallery->description,
                                    55
                                ) }}

                            </div>

                        @endif

                    </td>


                    {{-- LAYANAN --}}
                    <td>

                        {{ $gallery->service->name ?? '-' }}

                    </td>


                    {{-- STATUS --}}
                    <td>

                        @if ($gallery->status === 'published')

                            <span class="admin-status active">
                                Published
                            </span>

                        @else

                            <span class="admin-status inactive">
                                Hidden
                            </span>

                        @endif

                    </td>


                    {{-- ACTION --}}
                    <td>

                        <div class="admin-actions">

                            {{-- LIHAT PUBLIC --}}
                            <a
                                href="{{ route('gallery.index') }}"
                                target="_blank"
                                class="admin-action-btn"
                                title="Lihat Galeri Publik"
                            >
                                <i class="bi bi-eye"></i>
                            </a>


                            {{-- EDIT --}}
                            <a
                                href="{{ route(
                                    'admin.galleries.edit',
                                    $gallery->id
                                ) }}"
                                class="admin-action-btn edit"
                                title="Edit"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>


                            {{-- DELETE --}}
                            <form
                                action="{{ route(
                                    'admin.galleries.destroy',
                                    $gallery->id
                                ) }}"
                                method="POST"
                                onsubmit="return confirm(
                                    'Yakin ingin menghapus galeri ini?'
                                )"
                            >

                                @csrf
                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="admin-action-btn delete"
                                    title="Hapus"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="6"
                        class="admin-empty"
                    >
                        Belum ada data galeri.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


@endsection