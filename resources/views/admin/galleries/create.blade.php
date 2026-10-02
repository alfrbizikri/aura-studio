@extends('layouts.admin')

@section('title', 'Tambah Galeri - Admin Aura Studio')

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
            Tambah Galeri
        </h1>


        <p>
            Tambahkan foto baru ke galeri Aura Studio.
        </p>

    </div>

</div>



<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Galeri
        </h2>

        <p>
            Lengkapi informasi galeri di bawah ini.
        </p>

    </div>


    <form
        action="{{ route('admin.galleries.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        @include('admin.galleries._form')


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

                Simpan Galeri
            </button>

        </div>

    </form>

</div>


@endsection