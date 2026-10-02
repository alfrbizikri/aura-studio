@extends('layouts.admin')

@section('title', 'Kelola Cabang - Aura Studio')

@section('content')


<div class="admin-page-header">

    <div>

        <span class="admin-label">
            MASTER DATA
        </span>

        <h1>
            Kelola Cabang
        </h1>

        <p>
            Kelola seluruh cabang Aura Studio.
        </p>

    </div>


    <a
        href="{{ route('admin.branches.create') }}"
        class="admin-btn-primary"
    >

        <i class="bi bi-plus-lg"></i>

        Tambah Cabang

    </a>

</div>



{{-- PESAN BERHASIL --}}
@if (session('success'))

    <div class="admin-alert success">

        <i class="bi bi-check-circle"></i>

        {{ session('success') }}

    </div>

@endif



{{-- PESAN ERROR --}}
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
            Total Cabang
        </span>

        <strong>
            {{ $branches->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Cabang Aktif
        </span>

        <strong>
            {{ $branches->where('status', 'active')->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Tidak Aktif
        </span>

        <strong>
            {{ $branches->where('status', 'inactive')->count() }}
        </strong>

    </div>

</div>



{{-- TABLE --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Cabang
        </h2>

        <p>
            Data cabang yang tersimpan pada sistem.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama Cabang</th>

                    <th>Alamat</th>

                    <th>Telepon</th>

                    <th>Jam Operasional</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($branches as $branch)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>


                    <td>

                        <strong>
                            {{ $branch->name }}
                        </strong>

                    </td>


                    <td>

                        {{ \Illuminate\Support\Str::limit(
                            $branch->address,
                            45
                        ) }}

                    </td>


                    <td>

                        {{ $branch->phone ?? '-' }}

                    </td>


                    <td>

                        {{ \Carbon\Carbon::parse(
                            $branch->opening_time
                        )->format('H:i') }}

                        -

                        {{ \Carbon\Carbon::parse(
                            $branch->closing_time
                        )->format('H:i') }}

                    </td>


                    <td>

                        @if ($branch->status === 'active')

                            <span class="admin-status active">
                                Aktif
                            </span>

                        @else

                            <span class="admin-status inactive">
                                Tidak Aktif
                            </span>

                        @endif

                    </td>


                    <td>

                        <div class="admin-actions">


                            {{-- LIHAT --}}
                            <a
                                href="{{ route(
                                    'branches.show',
                                    $branch->id
                                ) }}"
                                target="_blank"
                                class="admin-action-btn"
                                title="Lihat"
                            >

                                <i class="bi bi-eye"></i>

                            </a>


                            {{-- EDIT --}}
                            <a
                                href="{{ route(
                                    'admin.branches.edit',
                                    $branch->id
                                ) }}"
                                class="admin-action-btn edit"
                                title="Edit"
                            >

                                <i class="bi bi-pencil"></i>

                            </a>


                            {{-- DELETE --}}
                            <form
                                action="{{ route(
                                    'admin.branches.destroy',
                                    $branch->id
                                ) }}"
                                method="POST"
                                onsubmit="return confirm(
                                    'Yakin ingin menghapus cabang ini?'
                                )"
                            >

                                @csrf
                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="admin-action-btn delete"
                                    title="Hapus"
                                >

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="7"
                        class="admin-empty"
                    >

                        Belum ada data cabang.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


@endsection