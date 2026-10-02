@extends('layouts.app')

@section('title', 'Lupa Password - Aura Studio')

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
                        Pulihkan
                        <em>Akun Anda.</em>
                    </h1>

                    <p>
                        Masukkan email yang terdaftar.
                        Kami akan memberikan link untuk membuat
                        password baru.
                    </p>

                </div>

                <div class="auth-visual-decoration">

                    <div class="auth-circle"></div>

                    <div class="auth-camera-card">
                        <i class="bi bi-key"></i>

                        <span>Account Recovery</span>

                        <strong>
                            Kembali mengakses akun Anda
                        </strong>
                    </div>

                </div>

            </div>


            {{-- FORM --}}
            <div class="auth-form-wrapper">

                <div class="auth-form-card">

                    <div class="auth-form-header">

                        <span>PEMULIHAN AKUN</span>

                        <h2>Lupa Password?</h2>

                        <p>
                            Masukkan email akun customer Aura Studio.
                        </p>

                    </div>


                    @if (session('success'))

                        <div class="auth-alert auth-alert-success">
                            {{ session('success') }}
                        </div>

                    @endif


                    @if ($errors->any())

                        <div class="auth-alert auth-alert-error">

                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach

                        </div>

                    @endif


                    <form
                        action="{{ route('password.email') }}"
                        method="POST"
                    >

                        @csrf


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
                                    required
                                    autofocus
                                >

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="auth-submit-btn"
                        >
                            <span>Kirim Link Reset</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>

                    </form>


                    <div class="auth-form-footer">

                        <a href="{{ route('login') }}">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection