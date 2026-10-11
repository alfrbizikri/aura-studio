@extends('layouts.admin')

@section('title', 'Kelola Fotografer - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-photographers.css') }}">
@endpush


@section('content')

<div class="admin-page-header">

    <div>

        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Kelola Fotografer
        </h1>

        <p>
            Kelola data fotografer pada
            setiap cabang Aura Studio.
        </p>

    </div>


    <a
        href="{{ route('admin.photographers.create') }}"
        class="admin-btn-primary">

        <i class="bi bi-plus-lg"></i>

        Tambah Fotografer

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
            Total Fotografer
        </span>

        <strong>
            {{ $photographers->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Aktif
        </span>

        <strong>
            {{ $photographers->where('status', 'active')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Cuti
        </span>

        <strong>
            {{ $photographers->where('status', 'leave')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Nonaktif
        </span>

        <strong>
            {{ $photographers->where('status', 'inactive')->count() }}
        </strong>

    </div>

</div>


<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Fotografer
        </h2>

        <p>
            Seluruh fotografer yang
            tersimpan di sistem.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>
                    <th>Fotografer</th>
                    <th>Cabang</th>
                    <th>Spesialisasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>


            <tbody>

                @forelse ($photographers as $photographer)

                    <tr>

                        {{-- FOTOGRAFER --}}
                        <td>

                            <div class="admin-photographer-profile">

                                <div class="admin-photographer-photo">

                                    @if ($photographer->photo)

                                        <img
                                            src="{{ asset(
                                                'storage/' . $photographer->photo
                                            ) }}"
                                            alt="{{ $photographer->name }}">

                                    @else

                                        <div class="admin-photographer-placeholder">

                                            <i class="bi bi-person"></i>

                                        </div>

                                    @endif

                                </div>


                                <div class="admin-photographer-info">

                                    <strong>
                                        {{ $photographer->name }}
                                    </strong>


                                    @if ($photographer->description)

                                        <small>

                                            {{ \Illuminate\Support\Str::limit(
                                                $photographer->description,
                                                55
                                            ) }}

                                        </small>

                                    @endif

                                </div>

                            </div>

                        </td>


                        {{-- CABANG --}}
                        <td>

                            <span class="admin-photographer-branch">

                                <i class="bi bi-building"></i>

                                {{ $photographer->branch?->name ?? '-' }}

                            </span>

                        </td>


                        {{-- SPESIALISASI --}}
                        <td>

                            @if ($photographer->specialization)

                                <span>
                                    {{ $photographer->specialization }}
                                </span>

                            @else

                                <span style="color: var(--a-soft)">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if ($photographer->status === 'active')

                                <span class="admin-status active">
                                    Aktif
                                </span>

                            @elseif ($photographer->status === 'leave')

                                <span class="admin-status">
                                    Cuti
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
                                        'admin.photographers.edit',
                                        $photographer
                                    ) }}"
                                    class="admin-action-btn edit">

                                    <i class="bi bi-pencil"></i>

                                    Edit

                                </a>


                                <form
                                    action="{{ route(
                                        'admin.photographers.destroy',
                                        $photographer
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus fotografer ini?'
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

                            Belum ada fotografer yang tersedia.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection