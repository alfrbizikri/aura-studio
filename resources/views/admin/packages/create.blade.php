@extends('layouts.admin')

@section('title', 'Tambah Paket - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-packages.css') }}">
@endpush


@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.packages.index') }}"
            class="admin-back-link">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>


        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Tambah Paket
        </h1>

        <p>
            Tambahkan paket baru untuk
            layanan Aura Studio.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Paket
        </h2>

        <p>
            Tentukan layanan, harga dan detail paket.
        </p>

    </div>


    <form
        action="{{ route('admin.packages.store') }}"
        method="POST">

        @csrf


        <div class="admin-form-grid">

            {{-- LAYANAN --}}
            <div class="admin-form-group">

                <label for="service_id">
                    Layanan <span>*</span>
                </label>

                <select
                    id="service_id"
                    name="service_id"
                    required>

                    <option value="">
                        Pilih layanan
                    </option>

                    @foreach ($services as $service)

                        <option
                            value="{{ $service->id }}"
                            @selected(
                                old('service_id') == $service->id
                            )>

                            {{ $service->name }}

                            @if ($service->status !== 'active')
                                — Nonaktif
                            @endif

                        </option>

                    @endforeach

                </select>

                @error('service_id')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- STATUS --}}
            <div class="admin-form-group">

                <label for="is_active">
                    Status <span>*</span>
                </label>

                <select
                    id="is_active"
                    name="is_active"
                    required>

                    <option
                        value="1"
                        @selected(old('is_active', '1') == '1')>
                        Aktif
                    </option>

                    <option
                        value="0"
                        @selected(old('is_active') == '0')>
                        Nonaktif
                    </option>

                </select>

            </div>


            {{-- NAMA --}}
            <div class="admin-form-group full">

                <label for="name">
                    Nama Paket <span>*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Premium Portrait"
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
                    rows="4"
                    placeholder="Jelaskan paket ini...">{{ old('description') }}</textarea>

                @error('description')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- HARGA --}}
            <div class="admin-form-group">

                <label for="price">
                    Harga <span>*</span>
                </label>

                <input
                    type="number"
                    id="price"
                    name="price"
                    value="{{ old('price') }}"
                    min="0"
                    step="1000"
                    placeholder="250000"
                    required>

                @error('price')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- DURASI --}}
            <div class="admin-form-group">

                <label for="duration_minutes">
                    Durasi (menit) <span>*</span>
                </label>

                <input
                    type="number"
                    id="duration_minutes"
                    name="duration_minutes"
                    value="{{ old('duration_minutes') }}"
                    min="1"
                    placeholder="60"
                    required>

                @error('duration_minutes')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- MAX PEOPLE --}}
            <div class="admin-form-group">

                <label for="max_people">
                    Maksimal Orang
                </label>

                <input
                    type="number"
                    id="max_people"
                    name="max_people"
                    value="{{ old('max_people') }}"
                    min="1"
                    placeholder="5">

                @error('max_people')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- PHOTO COUNT --}}
            <div class="admin-form-group">

                <label for="photo_count">
                    Jumlah Foto
                </label>

                <input
                    type="number"
                    id="photo_count"
                    name="photo_count"
                    value="{{ old('photo_count') }}"
                    min="1"
                    placeholder="20">

                @error('photo_count')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- INCLUDE --}}
            <div class="admin-form-group full">

                <label for="package_includes">
                    Isi Paket
                </label>

                <textarea
                    id="package_includes"
                    name="package_includes"
                    rows="6"
                    placeholder="1 fotografer&#10;20 file digital&#10;1 cetak foto">{{ old('package_includes') }}</textarea>

                <small class="admin-help-text">
                    Tulis satu fasilitas per baris agar
                    mudah ditampilkan pada halaman paket.
                </small>

                @error('package_includes')
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

                Simpan Paket

            </button>


            <a
                href="{{ route('admin.packages.index') }}"
                class="admin-btn-secondary">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection