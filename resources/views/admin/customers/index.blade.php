@extends('layouts.admin')

@section('title', 'Kelola Customer - Aura Studio')

@section('content')

<div class="admin-page-header">

    <div>

        <span class="admin-label">
            CUSTOMER
        </span>

        <h1>
            Kelola Customer
        </h1>

        <p>
            Lihat seluruh customer yang terdaftar di Aura Studio.
        </p>

    </div>

</div>


{{-- STATISTIK --}}
<div class="admin-stats">

    <div class="admin-stat-card">

        <span>
            Total Customer
        </span>

        <strong>
            {{ $customers->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Sudah Pernah Booking
        </span>

        <strong>
            {{ $customers->where('bookings_count', '>', 0)->count() }}
        </strong>

    </div>


    <div class="admin-stat-card">

        <span>
            Belum Pernah Booking
        </span>

        <strong>
            {{ $customers->where('bookings_count', 0)->count() }}
        </strong>

    </div>

</div>


{{-- TABLE --}}
<div class="admin-table-card">

    <div class="admin-table-title">

        <h2>
            Daftar Customer
        </h2>

        <p>
            Data akun customer yang terdaftar pada sistem.
        </p>

    </div>


    <div class="admin-table-responsive">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama</th>

                    <th>Email</th>

                    <th>Telepon</th>

                    <th>Total Booking</th>

                    <th>Terdaftar</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

            @forelse ($customers as $customer)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>


                    <td>

                        <strong>
                            {{ $customer->name }}
                        </strong>

                    </td>


                    <td>
                        {{ $customer->email }}
                    </td>


                    <td>
                        {{ $customer->phone ?? '-' }}
                    </td>


                    <td>
                        {{ $customer->bookings_count }}
                    </td>


                    <td>

                        {{ $customer->created_at
                            ? $customer->created_at->format('d/m/Y')
                            : '-'
                        }}

                    </td>


                    <td>

                        <div class="admin-actions">

                            <a
                                href="{{ route(
                                    'admin.customers.show',
                                    $customer->id
                                ) }}"
                                class="admin-action-btn"
                                title="Lihat Detail"
                            >

                                <i class="bi bi-eye"></i>

                            </a>

                        </div>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="7"
                        class="admin-empty"
                    >

                        Belum ada customer terdaftar.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection