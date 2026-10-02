@extends('layouts.admin')

@section('title', 'Tambah Cabang - Aura Studio')

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

        <h1>Tambah Cabang</h1>

        <p>
            Tambahkan cabang baru Aura Studio.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Cabang
        </h2>

        <p>
            Lengkapi seluruh data cabang.
        </p>

    </div>


    <form
        action="{{ route('admin.branches.store') }}"
        method="POST"
    >

        @csrf

        @include('admin.branches._form')


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
                Simpan Cabang
            </button>

        </div>

    </form>

</div>

@endsection