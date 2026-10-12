@extends('layouts.admin')

@section('title', 'Kelola Studio Room - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-studio-rooms.css') }}">
@endpush


@section('content')

    <div class="admin-page-header">

        <div>

            <span class="admin-label">
                MASTER DATA
            </span>

            <h1>
                Kelola Studio Room
            </h1>

            <p>
                Kelola ruangan studio pada setiap
                cabang Aura Studio.
            </p>

        </div>


        <a href="{{ route('admin.studio-rooms.create') }}" class="admin-btn-primary">

            <i class="bi bi-plus-lg"></i>

            Tambah Studio Room

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

            <span>
                Total Studio Room
            </span>

            <strong>
                {{ $studioRooms->count() }}
            </strong>

        </div>


        <div class="admin-stat-card">

            <span>
                Aktif
            </span>

            <strong>
                {{ $studioRooms->where('status', 'active')->count() }}
            </strong>

        </div>


        <div class="admin-stat-card">

            <span>
                Maintenance
            </span>

            <strong>
                {{ $studioRooms->where('status', 'maintenance')->count() }}
            </strong>

        </div>


        <div class="admin-stat-card">

            <span>
                Nonaktif
            </span>

            <strong>
                {{ $studioRooms->where('status', 'inactive')->count() }}
            </strong>

        </div>

    </div>


    <div class="admin-table-card">

        <div class="admin-table-title">

            <h2>
                Daftar Studio Room
            </h2>

            <p>
                Seluruh ruangan studio yang tersedia
                pada setiap cabang.
            </p>

        </div>


        <div class="admin-table-responsive">

            <table class="admin-table">

                <thead>

                    <tr>
                        <th>Room</th>
                        <th>Cabang</th>
                        <th>Layanan</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($studioRooms as $studioRoom)

                                    <tr>

                                        <td>

                                            <div style="
                                                        display:flex;
                                                        align-items:center;
                                                        gap:12px;
                                                    ">

                                                <div class="admin-room-icon">

                                                    <i class="bi bi-door-open"></i>

                                                </div>

                                                <strong>
                                                    {{ $studioRoom->name }}
                                                </strong>

                                            </div>

                                        </td>


                                        <td>

                                            <span class="admin-room-branch">

                                                <i class="bi bi-building"></i>

                                                {{ $studioRoom->branch?->name ?? '-' }}

                                            </span>

                                        </td>


                                        <td>

                                            @if ($studioRoom->service)

                                                <span class="admin-room-branch">

                                                    <i class="bi bi-camera"></i>

                                                    {{ $studioRoom->service->name }}

                                                </span>

                                            @else

                                                <span class="admin-status inactive">
                                                    Belum Ditentukan
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if ($studioRoom->description)

                                                                <div class="admin-room-description">

                                                                    {{ \Illuminate\Support\Str::limit(
                                                    $studioRoom->description,
                                                    100
                                                ) }}

                                                                </div>

                                            @else

                                                <span style="color:var(--a-soft)">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if ($studioRoom->status === 'active')

                                                <span class="admin-status active">
                                                    Aktif
                                                </span>

                                            @elseif ($studioRoom->status === 'maintenance')

                                                <span class="admin-status">
                                                    Maintenance
                                                </span>

                                            @else

                                                <span class="admin-status inactive">
                                                    Nonaktif
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <div class="admin-actions">

                                                <a href="{{ route(
                            'admin.studio-rooms.edit',
                            $studioRoom
                        ) }}" class="admin-action-btn edit">

                                                    <i class="bi bi-pencil"></i>

                                                    Edit

                                                </a>


                                                <form action="{{ route(
                            'admin.studio-rooms.destroy',
                            $studioRoom
                        ) }}" method="POST" onsubmit="return confirm(
                                                            'Yakin ingin menghapus studio room ini?'
                                                        )">

                                                    @csrf
                                                    @method('DELETE')


                                                    <button type="submit" class="admin-action-btn delete">

                                                        <i class="bi bi-trash"></i>

                                                        Hapus

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="admin-empty">

                                Belum ada studio room yang tersedia.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection