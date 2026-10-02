@extends('layouts.admin')

@section('title', 'Edit Galeri - Admin Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-gallery.css') }}"
    >
@endpush


@section('content')


<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.galleries.index') }}"
            class="admin-back-link"
        >
            <i class="bi bi-arrow-left"></i>

            Kembali
        </a>


        <h1>
            Edit Galeri
        </h1>


        <p>
            Perbarui data {{ $gallery->title }}.
        </p>

    </div>

</div>



<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Galeri
        </h2>

        <p>
            Ubah informasi yang diperlukan.
        </p>

    </div>


    <form
        action="{{ route(
            'admin.galleries.update',
            $gallery->id
        ) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        @include(
            'admin.galleries._form',
            ['gallery' => $gallery]
        )


        <div class="admin-form-actions">

            <a
                href="{{ route('admin.galleries.index') }}"
                class="admin-btn-secondary"
            >
                Batal
            </a>


            <button
                type="submit"
                class="admin-btn-primary"
            >
                <i class="bi bi-check-lg"></i>

                Simpan Perubahan
            </button>

        </div>

    </form>

</div>


@endsection