@extends('layouts.admin')

@section('title', 'Tambah Fotografer - Aura Studio')

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
            Tambah Fotografer
        </h1>

        <p>
            Tambahkan fotografer baru
            ke Aura Studio.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Fotografer
        </h2>

        <p>
            Isi profil, cabang,
            spesialisasi dan status fotografer.
        </p>

    </div>


    <form
        action="{{ route('admin.photographers.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf


        <div class="admin-form-grid">

            {{-- CABANG --}}
            <div class="admin-form-group">

                <label for="branch_id">
                    Cabang <span>*</span>
                </label>

                <select
                    id="branch_id"
                    name="branch_id"
                    required>

                    <option value="">
                        Pilih cabang
                    </option>

                    @foreach ($branches as $branch)

                        <option
                            value="{{ $branch->id }}"
                            @selected(
                                old('branch_id') == $branch->id
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


            {{-- STATUS --}}
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
                            old('status', 'active') === 'active'
                        )>
                        Aktif
                    </option>

                    <option
                        value="leave"
                        @selected(
                            old('status') === 'leave'
                        )>
                        Cuti
                    </option>

                    <option
                        value="inactive"
                        @selected(
                            old('status') === 'inactive'
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


            {{-- NAMA --}}
            <div class="admin-form-group full">

                <label for="name">
                    Nama Fotografer <span>*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Raka Pratama"
                    required>

                @error('name')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- SPESIALISASI --}}
            <div class="admin-form-group full">

                <label for="specialization">
                    Spesialisasi
                </label>

                <input
                    type="text"
                    id="specialization"
                    name="specialization"
                    value="{{ old('specialization') }}"
                    placeholder="Contoh: Portrait, Wedding, Family">

                <small class="admin-help-text">
                    Jika memiliki beberapa spesialisasi,
                    pisahkan dengan koma.
                </small>

                @error('specialization')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- FOTO --}}
            <div class="admin-form-group full">

                <label for="photo">
                    Foto Fotografer
                </label>

                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="admin-help-text">
                    JPG, JPEG, PNG atau WEBP.
                    Maksimal 2 MB.
                </small>

                @error('photo')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- DESKRIPSI --}}
            <div class="admin-form-group full">

                <label for="description">
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Tuliskan profil singkat fotografer...">{{ old('description') }}</textarea>

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

                Simpan Fotografer

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