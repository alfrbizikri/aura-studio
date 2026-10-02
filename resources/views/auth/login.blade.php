@extends('layouts.app')

@section('title', 'Masuk - Aura Studio')

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
                        Selamat Datang
                        <em>Kembali.</em>
                    </h1>

                    <p>
                        Masuk untuk melanjutkan proses booking,
                        melihat riwayat pemesanan, pembayaran,
                        dan informasi akun Anda.
                    </p>

                </div>


                <div class="auth-visual-decoration">

                    <div class="auth-circle"></div>

                    <div class="auth-camera-card">

                        <i class="bi bi-camera"></i>

                        <span>
                            Aura Studio
                        </span>

                        <strong>
                            Capture Your Story
                        </strong>

                    </div>

                </div>

            </div>


            {{-- FORM --}}
            <div class="auth-form-wrapper">

                <div class="auth-form-card">

                    <div class="auth-form-header">

                        <span>
                            LOGIN CUSTOMER
                        </span>

                        <h2>
                            Masuk ke Akun
                        </h2>

                        <p>
                            Masukkan email dan password
                            yang telah terdaftar.
                        </p>

                    </div>


                    <form
                        action="{{ route('login.process') }}"
                        method="POST"
                    >

                        @csrf


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
                                    autofocus
                                >

                            </div>

                            @error('email')

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
                                    placeholder="Masukkan password"
                                    required
                                >

                            </div>

                            @error('password')

                                <small class="auth-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- REMEMBER --}}
                        <div class="auth-form-options">

                            <label class="auth-checkbox">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                >

                                <span>
                                    Ingat saya
                                </span>

                            </label>

                        </div>


                        <button
                            type="submit"
                            class="auth-submit-btn"
                        >

                            Masuk

                            <i class="bi bi-arrow-right"></i>

                        </button>

                    </form>


                    <div class="auth-form-footer">

                        <span>
                            Belum memiliki akun?
                        </span>

                        <a href="{{ route('register') }}">
                            Daftar Sekarang
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection