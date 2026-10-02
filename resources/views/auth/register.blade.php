@extends('layouts.app')

@section('title', 'Daftar - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')

<section class="auth-page">

    <div class="container">

        <div class="auth-layout register-layout">

            {{-- VISUAL --}}
            <div class="auth-visual">

                <div class="auth-visual-content">

                    <span class="auth-kicker">
                        AURA STUDIO
                    </span>

                    <h1>
                        Mulai Ceritamu
                        <em>Bersama Kami.</em>
                    </h1>

                    <p>
                        Buat akun customer untuk melakukan
                        booking serta mengakses layanan Aura
                        Studio dengan lebih mudah.
                    </p>

                </div>


                <div class="auth-visual-decoration">

                    <div class="auth-circle"></div>

                    <div class="auth-camera-card">

                        <i class="bi bi-person-plus"></i>

                        <span>
                            Aura Studio
                        </span>

                        <strong>
                            Create Your Account
                        </strong>

                    </div>

                </div>

            </div>


            {{-- FORM --}}
            <div class="auth-form-wrapper">

                <div class="auth-form-card">

                    <div class="auth-form-header">

                        <span>
                            REGISTER CUSTOMER
                        </span>

                        <h2>
                            Buat Akun
                        </h2>

                        <p>
                            Isi data berikut untuk
                            membuat akun customer.
                        </p>

                    </div>


                    <form
                        action="{{ route('register.process') }}"
                        method="POST"
                    >

                        @csrf


                        {{-- NAME --}}
                        <div class="auth-form-group">

                            <label for="name">
                                Nama Lengkap
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-person"></i>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Nama lengkap"
                                    required
                                >

                            </div>

                            @error('name')

                                <small class="auth-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- EMAIL --}}
                        <div class="auth-form-group">

                            <label for="email">
                                Email
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-envelope"></i>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required
                                >

                            </div>

                            @error('email')

                                <small class="auth-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- PHONE --}}
                        <div class="auth-form-group">

                            <label for="phone">
                                Nomor Telepon
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-telephone"></i>

                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="08xxxxxxxxxx"
                                >

                            </div>

                            @error('phone')

                                <small class="auth-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- PASSWORD --}}
                        <div class="auth-form-group">

                            <label for="password">
                                Password
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-lock"></i>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Minimal 8 karakter"
                                    required
                                >

                            </div>

                            @error('password')

                                <small class="auth-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- CONFIRM PASSWORD --}}
                        <div class="auth-form-group">

                            <label for="password_confirmation">
                                Konfirmasi Password
                            </label>

                            <div class="auth-input-wrapper">

                                <i class="bi bi-shield-lock"></i>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ulangi password"
                                    required
                                >

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="auth-submit-btn"
                        >

                            Buat Akun

                            <i class="bi bi-arrow-right"></i>

                        </button>

                    </form>


                    <div class="auth-form-footer">

                        <span>
                            Sudah memiliki akun?
                        </span>

                        <a href="{{ route('login') }}">
                            Masuk
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection