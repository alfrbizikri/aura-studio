@extends('layouts.app')

@section('title', 'Login Customer - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')

<section class="auth-page">

    <div class="container">

        <div class="auth-layout">


            {{-- =========================
                 BAGIAN VISUAL
                 ========================= --}}
            <div class="auth-visual">

                <div class="auth-visual-content">

                    <span class="auth-kicker">
                        AURA STUDIO
                    </span>

                    <h1>
                        Welcome
                        <em>Back.</em>
                    </h1>

                    <p>
                        Masuk ke akun Aura Studio untuk melihat
                        riwayat booking, pembayaran, dan mengelola
                        profil Anda.
                    </p>

                </div>


                <div class="auth-visual-decoration">

                    <div class="auth-circle"></div>

                    <div class="auth-camera-card">

                        <i class="bi bi-camera"></i>

                        <span>
                            YOUR MOMENT
                        </span>

                        <strong>
                            Setiap momen layak untuk diabadikan.
                        </strong>

                    </div>

                </div>

            </div>



            {{-- =========================
                 BAGIAN FORM LOGIN
                 ========================= --}}
            <div class="auth-form-wrapper">

                <div class="auth-form-card">


                    <div class="auth-form-header">

                        <span>
                            CUSTOMER ACCESS
                        </span>

                        <h2>
                            Login
                        </h2>

                        <p>
                            Masukkan email dan password akun Anda.
                        </p>

                    </div>



                    {{-- SUCCESS MESSAGE --}}
                    @if (session('success'))

                        <div class="auth-alert auth-alert-success">

                            <i class="bi bi-check-circle"></i>

                            <span>
                                {{ session('success') }}
                            </span>

                        </div>

                    @endif



                    {{-- ERROR --}}
                    @if ($errors->any())

                        <div class="auth-alert auth-alert-error">

                            <i class="bi bi-exclamation-circle"></i>

                            <div>

                                @foreach ($errors->all() as $error)

                                    <div>
                                        {{ $error }}
                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif



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
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    autocomplete="email"
                                    required
                                    autofocus
                                >

                            </div>

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
                                    name="password"
                                    id="password"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
                                    required
                                >

                            </div>

                        </div>



                        {{-- OPTIONS --}}
                        <div class="auth-form-options">

                            <label class="auth-checkbox">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    {{ old('remember') ? 'checked' : '' }}
                                >

                                <span>
                                    Ingat saya
                                </span>

                            </label>


                            <div class="auth-forgot">

                                <a href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>

                            </div>

                        </div>



                        {{-- LOGIN BUTTON --}}
                        <button
                            type="submit"
                            class="auth-submit-btn"
                        >

                            <span>
                                Login
                            </span>

                            <i class="bi bi-arrow-right"></i>

                        </button>

                    </form>



                    {{-- REGISTER --}}
                    <div class="auth-form-footer">

                        <span>
                            Belum punya akun?
                        </span>

                        <a href="{{ route('register') }}">
                            Daftar sekarang
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection