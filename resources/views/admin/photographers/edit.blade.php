@extends('layouts.admin')

@section('title', 'Edit Fotografer - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-photographers.css') }}">
@endpush


@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.photographers.index') }}"
            class="admin-back-link">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>


        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Edit Fotografer
        </h1>

        <p>
            Perbarui profil
            {{ $photographer->name }}.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Fotografer
        </h2>

        <p>
            Perbarui profil, cabang,
            spesialisasi atau status fotografer.
        </p>

    </div>


    <form
        action="{{ route(
            'admin.photographers.update',
            $photographer
        ) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')


        <div class="admin-form-grid">

            <div class="admin-form-group">

                <label for="branch_id">
                    Cabang <span>*</span>
                </label>

                <select
                    id="branch_id"
                    name="branch_id"
                    required>

                    @foreach ($branches as $branch)

                        <option
                            value="{{ $branch->id }}"
                            @selected(
                                old(
                                    'branch_id',
                                    $photographer->branch_id
                                ) == $branch->id
                            )>

                            {{ $branch->name }}

                            @if ($branch->status !== 'active')
                                — Nonaktif
                            @endif

                        </option>

                    @endforeach

                </select>

                @error('branch_id')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="admin-form-group">

                <label for="status">
                    Status <span>*</span>
                </label>

                <select
                    id="status"
                    name="status"
                    required>

                    <option
                        value="active"
                        @selected(
                            old(
                                'status',
                                $photographer->status
                            ) === 'active'
                        )>
                        Aktif
                    </option>

                    <option
                        value="leave"
                        @selected(
                            old(
                                'status',
                                $photographer->status
                            ) === 'leave'
                        )>
                        Cuti
                    </option>

                    <option
                        value="inactive"
                        @selected(
                            old(
                                'status',
                                $photographer->status
                            ) === 'inactive'
                        )>
                        Nonaktif
                    </option>

                </select>

                @error('status')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="admin-form-group full">

                <label for="name">
                    Nama Fotografer <span>*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old(
                        'name',
                        $photographer->name
                    ) }}"
                    required>

                @error('name')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="admin-form-group full">

                <label for="specialization">
                    Spesialisasi
                </label>

                <input
                    type="text"
                    id="specialization"
                    name="specialization"
                    value="{{ old(
                        'specialization',
                        $photographer->specialization
                    ) }}"
                    placeholder="Portrait, Wedding, Family">

                <small class="admin-help-text">
                    Pisahkan beberapa spesialisasi
                    menggunakan koma.
                </small>

                @error('specialization')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="admin-form-group full">

                <label for="photo">
                    Foto Fotografer
                </label>


                @if ($photographer->photo)

                    <div class="admin-current-photographer-photo">

                        <img
                            src="{{ asset(
                                'storage/' . $photographer->photo
                            ) }}"
                            alt="{{ $photographer->name }}">

                        <span>
                            Foto saat ini
                        </span>

                    </div>

                @endif


                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="admin-help-text">
                    Kosongkan jika foto tidak ingin diganti.
                    Maksimal 2 MB.
                </small>

                @error('photo')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="admin-form-group full">

                <label for="description">
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5">{{ old(
                        'description',
                        $photographer->description
                    ) }}</textarea>

                @error('description')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>

        </div>


        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-btn-primary">

                <i class="bi bi-check-lg"></i>

                Simpan Perubahan

            </button>


            <a
                href="{{ route('admin.photographers.index') }}"
                class="admin-btn-secondary">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection