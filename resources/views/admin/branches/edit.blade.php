@extends('layouts.admin')

@section('title', 'Edit Cabang - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.branches.index') }}"
            class="admin-back-link"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <h1>Edit Cabang</h1>

        <p>
            Perbarui data {{ $branch->name }}.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Cabang
        </h2>

        <p>
            Ubah data yang diperlukan.
        </p>

    </div>


    <form
        action="{{ route(
            'admin.branches.update',
            $branch->id
        ) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        @include(
            'admin.branches._form',
            ['branch' => $branch]
        )


        <div class="admin-form-actions">

            <a
                href="{{ route('admin.branches.index') }}"
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