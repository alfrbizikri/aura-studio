@extends('layouts.admin')

@section('title', 'Kelola Paket - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-packages.css') }}">
@endpush


@section('content')

<div class="admin-page-header">

    <div>

        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Kelola Paket
        </h1>

        <p>
            Kelola paket dan harga layanan
            yang tersedia di Aura Studio.
        </p>

    </div>


    <a
        href="{{ route('admin.packages.create') }}"
        class="admin-btn-primary">

        <i class="bi bi-plus-lg"></i>

        Tambah Paket

    </a>

</div>


@if (session('success'))

    <div class="admin-alert success">

        <i class="bi bi-check-circle"></i>

        {{ session('success') }}

    </div>

@endif


@if (session('error'))

    <div class="admin-alert error">

        <i class="bi bi-exclamation-circle"></i>

        {{ session('error') }}

    </div>

@endif


{{-- STATISTIK --}}
<div class="admin-stats">

    <div class="admin-stat-card">

        <span>Total Paket</span>

        <strong>
            {{ $packages->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>Paket Aktif</span>

        <strong>
            {{ $packages->where('is_active', true)->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>Paket Nonaktif</span>

        <strong>
            {{ $packages->where('is_active', false)->count() }}
        </strong>

    </div>

</div>


<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Paket
        </h2>

        <p>
            Seluruh paket layanan yang
            tersimpan di sistem.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>
                    <th>Paket</th>
                    <th>Harga</th>
                    <th>Detail</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>


            <tbody>

                @forelse ($packages as $package)

                    <tr>

                        {{-- PAKET --}}
                        <td>

                            <span class="admin-package-service">

                                <i class="bi bi-camera"></i>

                                {{ $package->service?->name ?? '-' }}

                            </span>

                            <br>

                            <strong>
                                {{ $package->name }}
                            </strong>


                            @if ($package->description)

                                <div class="admin-package-description">

                                    {{ \Illuminate\Support\Str::limit(
                                        $package->description,
                                        90
                                    ) }}

                                </div>

                            @endif

                        </td>


                        {{-- HARGA --}}
                        <td>

                            <span class="admin-package-price">

                                Rp {{ number_format(
                                    $package->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </span>

                        </td>


                        {{-- DETAIL --}}
                        <td>

                            <div class="admin-package-meta">

                                <span>
                                    <i class="bi bi-clock"></i>

                                    {{ $package->duration_minutes }}
                                    menit
                                </span>


                                @if ($package->max_people)

                                    <span>
                                        <i class="bi bi-people"></i>

                                        Maks.
                                        {{ $package->max_people }}
                                    </span>

                                @endif


                                @if ($package->photo_count)

                                    <span>
                                        <i class="bi bi-images"></i>

                                        {{ $package->photo_count }}
                                        foto
                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if ($package->is_active)

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
                                        'admin.packages.edit',
                                        $package
                                    ) }}"
                                    class="admin-action-btn edit">

                                    <i class="bi bi-pencil"></i>

                                    Edit

                                </a>


                                <form
                                    action="{{ route(
                                        'admin.packages.destroy',
                                        $package
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus paket ini?'
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

                            Belum ada paket yang tersedia.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection