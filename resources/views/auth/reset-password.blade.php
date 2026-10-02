@extends('layouts.app')

@section('title', 'Reset Password - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')

<section class="auth-page">
    <div class="container">

        <div class="auth-layout">

            {{-- VISUAL --}}
            <div class="auth-visual">

                <div class="auth-visual-content">

                    <span class="auth-kicker">
                        AURA STUDIO
                    </span>

                    <h1>
                        Buat
                        <em>Password Baru.</em>
                    </h1>

                    <p>
                        Gunakan password baru yang mudah Anda ingat
                        namun tetap aman.
                    </p>

                </div>

                <div class="auth-visual-decoration">

                    <div class="auth-circle"></div>

                    <div class="auth-camera-card">

                        <i class="bi bi-shield-lock"></i>

                        <span>New Password</span>

                        <strong>
                            Amankan kembali akun Anda
                        </strong>

                    </div>

                </div>

            </div>


            {{-- FORM --}}
            <div class="auth-form-wrapper">

                <div class="auth-form-card">

                    <div class="auth-form-header">

                        <span>PASSWORD BARU</span>

                        <h2>Reset Password</h2>

                        <p>
                            Masukkan password baru untuk akun Anda.
                        </p>

                    </div>


                    @if ($errors->any())

                        <div class="auth-alert auth-alert-error">

                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach

                        </div>

                    @endif


                    <form
                        action="{{ route('password.update') }}"
                        method="POST"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="token"
                            value="{{ $token }}"
                        >


                        <div class="auth-form-group">

                            <label for="email">
                                Email
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-envelope"></i>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email', $email) }}"
                                    required
                                >

                            </div>

                        </div>


                        <div class="auth-form-group">

                            <label for="password">
                                Password Baru
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-lock"></i>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="Minimal 8 karakter"
                                    minlength="8"
                                    required
                                >

                            </div>

                        </div>


                        <div class="auth-form-group">

                            <label for="password_confirmation">
                                Konfirmasi Password
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-lock-fill"></i>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    placeholder="Ulangi password baru"
                                    minlength="8"
                                    required
                                >

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="auth-submit-btn"
                        >
                            <span>Simpan Password Baru</span>
                            <i class="bi bi-check-lg"></i>
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection