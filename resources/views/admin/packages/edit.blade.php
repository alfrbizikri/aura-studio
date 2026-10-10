@extends('layouts.admin')

@section('title', 'Edit Paket - Aura Studio')

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
            Edit Paket
        </h1>

        <p>
            Perbarui informasi
            {{ $package->name }}.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Paket
        </h2>

        <p>
            Perbarui layanan, harga,
            kapasitas dan detail paket.
        </p>

    </div>


    <form
        action="{{ route(
            'admin.packages.update',
            $package
        ) }}"
        method="POST">

        @csrf
        @method('PUT')


        <div class="admin-form-grid">

            <div class="admin-form-group">

                <label for="service_id">
                    Layanan <span>*</span>
                </label>

                <select
                    id="service_id"
                    name="service_id"
                    required>

                    @foreach ($services as $service)

                        <option
                            value="{{ $service->id }}"
                            @selected(
                                old(
                                    'service_id',
                                    $package->service_id
                                ) == $service->id
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
                        @selected(
                            old(
                                'is_active',
                                $package->is_active ? '1' : '0'
                            ) == '1'
                        )>
                        Aktif
                    </option>

                    <option
                        value="0"
                        @selected(
                            old(
                                'is_active',
                                $package->is_active ? '1' : '0'
                            ) == '0'
                        )>
                        Nonaktif
                    </option>

                </select>

            </div>


            <div class="admin-form-group full">

                <label for="name">
                    Nama Paket <span>*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old(
                        'name',
                        $package->name
                    ) }}"
                    required>

                @error('name')
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
                    rows="4">{{ old(
                        'description',
                        $package->description
                    ) }}</textarea>

            </div>


            <div class="admin-form-group">

                <label for="price">
                    Harga <span>*</span>
                </label>

                <input
                    type="number"
                    id="price"
                    name="price"
                    value="{{ old(
                        'price',
                        $package->price
                    ) }}"
                    min="0"
                    step="1000"
                    required>

            </div>


            <div class="admin-form-group">

                <label for="duration_minutes">
                    Durasi (menit) <span>*</span>
                </label>

                <input
                    type="number"
                    id="duration_minutes"
                    name="duration_minutes"
                    value="{{ old(
                        'duration_minutes',
                        $package->duration_minutes
                    ) }}"
                    min="1"
                    required>

            </div>


            <div class="admin-form-group">

                <label for="max_people">
                    Maksimal Orang
                </label>

                <input
                    type="number"
                    id="max_people"
                    name="max_people"
                    value="{{ old(
                        'max_people',
                        $package->max_people
                    ) }}"
                    min="1">

            </div>


            <div class="admin-form-group">

                <label for="photo_count">
                    Jumlah Foto
                </label>

                <input
                    type="number"
                    id="photo_count"
                    name="photo_count"
                    value="{{ old(
                        'photo_count',
                        $package->photo_count
                    ) }}"
                    min="1">

            </div>


            <div class="admin-form-group full">

                <label for="package_includes">
                    Isi Paket
                </label>

                <textarea
                    id="package_includes"
                    name="package_includes"
                    rows="6">{{ old(
                        'package_includes',
                        $package->package_includes
                    ) }}</textarea>

                <small class="admin-help-text">
                    Tulis satu fasilitas per baris.
                </small>

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
                href="{{ route('admin.packages.index') }}"
                class="admin-btn-secondary">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection