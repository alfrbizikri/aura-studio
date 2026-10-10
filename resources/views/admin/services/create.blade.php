@extends('layouts.admin')

@section('title', 'Tambah Layanan - Aura Studio')

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
            Tambah Layanan
        </h1>

        <p>
            Tambahkan layanan fotografi baru
            ke Aura Studio.
        </p>

    </div>

</div>


<div class="admin-form-card">

    <div class="admin-form-title">

        <h2>
            Informasi Layanan
        </h2>

        <p>
            Isi informasi dasar dan kebutuhan layanan.
        </p>

    </div>


    <form
        action="{{ route('admin.services.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf


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
                    value="{{ old('name') }}"
                    placeholder="Contoh: Professional Studio"
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
                    placeholder="Jelaskan layanan yang ditawarkan...">{{ old('description') }}</textarea>

                @error('description')
                    <small class="admin-field-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- GAMBAR --}}
            <div class="admin-form-group full">

                <label for="image">
                    Foto Layanan
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="admin-help-text">
                    Format JPG, JPEG, PNG atau WEBP.
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
                            @checked(old('requires_photographer'))>

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
                            @checked(old('requires_room'))>

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
                            @checked(old('is_on_location'))>

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
                            old('status', 'active') === 'active'
                        )>
                        Aktif
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

        </div>


        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-btn-primary">

                <i class="bi bi-check-lg"></i>

                Simpan Layanan

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