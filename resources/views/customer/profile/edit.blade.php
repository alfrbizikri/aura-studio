@extends('layouts.app')

@section('title', 'Profil Saya - Aura Studio')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/customer-profile.css') }}"
    >
@endpush

@section('content')

<section class="profile-page">

    <div class="container">

        <div class="profile-heading">

            <span class="profile-kicker">
                Akun Saya
            </span>

            <h1>Profil Customer</h1>

            <p>
                Kelola informasi akun yang digunakan
                untuk pemesanan di Aura Studio.
            </p>

        </div>


        <div class="profile-card">

            @if (session('success'))

                <div class="profile-alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>

            @endif


            @if ($errors->any())

                <div class="profile-alert-error">

                    <strong>
                        Periksa kembali data yang Anda masukkan.
                    </strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            <form
                action="{{ route('customer.profile.update') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                <div class="profile-photo-section">

                    <div class="profile-avatar">

                        @if (
                            $user->profile_image &&
                            \Illuminate\Support\Facades\Storage::disk('public')
                                ->exists($user->profile_image)
                        )

                            <img
                                src="{{ asset(
                                    'storage/' . $user->profile_image
                                ) }}"
                                alt="{{ $user->name }}"
                            >

                        @else

                            <i class="bi bi-person"></i>

                        @endif

                    </div>


                    <div>

                        <label for="profile_image">
                            Foto Profil
                        </label>

                        <input
                            type="file"
                            name="profile_image"
                            id="profile_image"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <small>
                            JPG, PNG atau WebP. Maksimal 5 MB.
                        </small>

                    </div>

                </div>


                <div class="profile-form-grid">

                    <div class="profile-form-group">

                        <label for="name">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $user->name) }}"
                            required
                        >

                    </div>


                    <div class="profile-form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $user->email) }}"
                            required
                        >

                    </div>


                    <div class="profile-form-group">

                        <label for="phone">
                            Nomor Telepon
                        </label>

                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone', $user->phone) }}"
                            placeholder="Contoh: 081234567890"
                        >

                    </div>

                </div>


                <div class="profile-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</section>

@endsection