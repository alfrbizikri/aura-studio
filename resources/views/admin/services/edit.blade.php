@extends('layouts.admin')

@section('title', 'Edit Layanan - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-services.css') }}">
@endpush


@section('content')

<div class="admin-page-header">

    <div>

        <a
            href="{{ route('admin.services.index') }}"
            class="admin-back-link">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>


        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Edit Layanan
        </h1>

        <p>
            Perbarui informasi
            {{ $service->name }}.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Layanan
        </h2>

        <p>
            Perbarui data dan kebutuhan layanan.
        </p>

    </div>


    <form
        action="{{ route(
            'admin.services.update',
            $service
        ) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')


        <div class="admin-form-grid">

            {{-- NAMA --}}
            <div class="admin-form-group full">

                <label for="name">
                    Nama Layanan <span>*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old(
                        'name',
                        $service->name
                    ) }}"
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
                    rows="5">{{ old(
                        'description',
                        $service->description
                    ) }}</textarea>

                @error('description')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- GAMBAR --}}
            <div class="admin-form-group full">

                <label>
                    Foto Layanan
                </label>


                @if ($service->image)

                    <div class="admin-current-service-image">

                        <img
                            src="{{ asset(
                                'storage/' . $service->image
                            ) }}"
                            alt="{{ $service->name }}">

                        <small>
                            Foto saat ini
                        </small>

                    </div>

                @endif


                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="admin-help-text">
                    Kosongkan jika foto tidak ingin diganti.
                    Maksimal 2 MB.
                </small>

                @error('image')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- KEBUTUHAN --}}
            <div class="admin-form-group full">

                <label>
                    Kebutuhan Layanan
                </label>

                <div class="admin-check-grid">

                    <label class="admin-check-card">

                        <input
                            type="checkbox"
                            name="requires_photographer"
                            value="1"
                            @checked(
                                old(
                                    'requires_photographer',
                                    $service->requires_photographer
                                )
                            )>

                        <span>

                            <strong>
                                Fotografer
                            </strong>

                            <small>
                                Layanan memerlukan fotografer.
                            </small>

                        </span>

                    </label>


                    <label class="admin-check-card">

                        <input
                            type="checkbox"
                            name="requires_room"
                            value="1"
                            @checked(
                                old(
                                    'requires_room',
                                    $service->requires_room
                                )
                            )>

                        <span>

                            <strong>
                                Studio Room
                            </strong>

                            <small>
                                Layanan memerlukan ruangan studio.
                            </small>

                        </span>

                    </label>


                    <label class="admin-check-card">

                        <input
                            type="checkbox"
                            name="is_on_location"
                            value="1"
                            @checked(
                                old(
                                    'is_on_location',
                                    $service->is_on_location
                                )
                            )>

                        <span>

                            <strong>
                                On Location
                            </strong>

                            <small>
                                Sesi dilakukan di lokasi customer.
                            </small>

                        </span>

                    </label>

                </div>

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
                            old(
                                'status',
                                $service->status
                            ) === 'active'
                        )>
                        Aktif
                    </option>

                    <option
                        value="inactive"
                        @selected(
                            old(
                                'status',
                                $service->status
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

        </div>


        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-btn-primary">

                <i class="bi bi-check-lg"></i>

                Simpan Perubahan

            </button>


            <a
                href="{{ route('admin.services.index') }}"
                class="admin-btn-secondary">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection