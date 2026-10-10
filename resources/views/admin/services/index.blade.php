@extends('layouts.admin')

@section('title', 'Kelola Layanan - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-services.css') }}">
@endpush


@section('content')

<div class="admin-page-header">

    <div>

        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Kelola Layanan
        </h1>

        <p>
            Kelola layanan fotografi yang tersedia
            di Aura Studio.
        </p>

    </div>


    <a
        href="{{ route('admin.services.create') }}"
        class="admin-btn-primary">

        <i class="bi bi-plus-lg"></i>

        Tambah Layanan

    </a>

</div>


{{-- ALERT SUCCESS --}}
@if (session('success'))

    <div class="admin-alert success">

        <i class="bi bi-check-circle"></i>

        {{ session('success') }}

    </div>

@endif


{{-- ALERT ERROR --}}
@if (session('error'))

    <div class="admin-alert error">

        <i class="bi bi-exclamation-circle"></i>

        {{ session('error') }}

    </div>

@endif


{{-- STATISTIK --}}
<div class="admin-stats">

    <div class="admin-stat-card">

        <span>
            Total Layanan
        </span>

        <strong>
            {{ $services->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Layanan Aktif
        </span>

        <strong>
            {{ $services->where('status', 'active')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Layanan Nonaktif
        </span>

        <strong>
            {{ $services->where('status', 'inactive')->count() }}
        </strong>

    </div>

</div>


{{-- TABEL --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Layanan
        </h2>

        <p>
            Seluruh layanan fotografi yang
            tersimpan di sistem.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>
                        Foto
                    </th>

                    <th>
                        Layanan
                    </th>

                    <th>
                        Kebutuhan
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($services as $service)

                    <tr>

                        {{-- FOTO --}}
                        <td>

                            <div class="admin-service-thumb">

                                @if ($service->image)

                                    <img
                                        src="{{ asset(
                                            'storage/' . $service->image
                                        ) }}"
                                        alt="{{ $service->name }}">

                                @else

                                    <div class="admin-service-placeholder">

                                        <i class="bi bi-camera"></i>

                                    </div>

                                @endif

                            </div>

                        </td>


                        {{-- INFO --}}
                        <td>

                            <strong>
                                {{ $service->name }}
                            </strong>

                            @if ($service->description)

                                <div
                                    style="
                                        max-width: 320px;
                                        margin-top: 4px;
                                        color: var(--a-soft);
                                        font-size: .82rem;
                                    ">

                                    {{ \Illuminate\Support\Str::limit(
                                        $service->description,
                                        85
                                    ) }}

                                </div>

                            @endif

                        </td>


                        {{-- KEBUTUHAN --}}
                        <td>

                            <div class="admin-service-features">

                                @if ($service->requires_photographer)

                                    <span class="admin-service-feature">

                                        <i class="bi bi-person-video2"></i>

                                        Fotografer

                                    </span>

                                @endif


                                @if ($service->requires_room)

                                    <span class="admin-service-feature">

                                        <i class="bi bi-door-open"></i>

                                        Studio

                                    </span>

                                @endif


                                @if ($service->is_on_location)

                                    <span class="admin-service-feature">

                                        <i class="bi bi-geo-alt"></i>

                                        On Location

                                    </span>

                                @endif


                                @if (
                                    !$service->requires_photographer
                                    && !$service->requires_room
                                    && !$service->is_on_location
                                )

                                    <span class="admin-service-feature">

                                        <i class="bi bi-dash"></i>

                                        Tidak ada

                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if ($service->status === 'active')

                                <span class="admin-status active">
                                    Aktif
                                </span>

                            @else

                                <span class="admin-status inactive">
                                    Nonaktif
                                </span>

                            @endif

                        </td>


                        {{-- AKSI --}}
                        <td>

                            <div class="admin-actions">

                                <a
                                    href="{{ route(
                                        'admin.services.edit',
                                        $service
                                    ) }}"
                                    class="admin-action-btn edit"
                                    title="Edit">

                                    <i class="bi bi-pencil"></i>

                                    Edit

                                </a>


                                <form
                                    action="{{ route(
                                        'admin.services.destroy',
                                        $service
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus layanan ini?'
                                    )">

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="admin-action-btn delete">

                                        <i class="bi bi-trash"></i>

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="admin-empty">

                            Belum ada layanan yang tersedia.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection