@extends('layouts.admin')

@section('title', 'Tambah Studio Room - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-studio-rooms.css') }}">
@endpush


@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.studio-rooms.index') }}"
            class="admin-back-link">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>


        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Tambah Studio Room
        </h1>

        <p>
            Tambahkan ruangan studio baru
            pada cabang Aura Studio.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Studio Room
        </h2>

        <p>
            Tentukan cabang, nama ruangan,
            deskripsi dan status.
        </p>

    </div>


    <form
        action="{{ route('admin.studio-rooms.store') }}"
        method="POST">

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
                        value="maintenance"
                        @selected(
                            old('status') === 'maintenance'
                        )>
                        Maintenance
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
                    Nama Studio Room <span>*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Studio A"
                    required>

                @error('name')
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
                    placeholder="Jelaskan fasilitas atau karakter studio room...">{{ old('description') }}</textarea>

                <small class="admin-help-text">
                    Deskripsi bersifat opsional.
                </small>

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

                Simpan Studio Room

            </button>


            <a
                href="{{ route('admin.studio-rooms.index') }}"
                class="admin-btn-secondary">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection