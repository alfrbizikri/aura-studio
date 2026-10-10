<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login Admin - Aura Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        rel="stylesheet"
        href="{{ asset('css/admin-login.css') }}">
</head>

<body>

    <main class="alg-page">

        {{-- PANEL MEREK --}}
        <aside class="alg-brand" aria-hidden="true">

            <div class="alg-brand-top">

                <span class="alg-mark">
                    <i class="bi bi-camera"></i>
                </span>

                <strong>
                    Aura Studio
                </strong>

            </div>

            <div class="alg-brand-copy">

                <span>
                    Admin Panel
                </span>

                <p>
                    Kelola cabang, galeri, jadwal, booking, dan pembayaran dalam satu tempat.
                </p>

            </div>

        </aside>


        {{-- FORM LOGIN --}}
        <section class="alg-panel">

            <div class="auth-card alg-card">

                <div class="auth-heading alg-heading">

                    <span>
                        Administration
                    </span>

                    <h1>
                        Login Admin
                    </h1>

                    <p>
                        Masuk untuk mengelola sistem Aura Studio.
                    </p>

                </div>


                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif


                @if (session('error'))
                    <div class="alert alert-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif


                <form
                    method="POST"
                    action="{{ route('admin.login.process') }}"
                    id="adminLoginForm">

                    @csrf


                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <div class="alg-input">

                            <i class="bi bi-envelope"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="nama@aurastudio.id"
                                required
                                autofocus>

                        </div>

                        @error('email')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <div class="alg-input">

                            <i class="bi bi-lock"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required>

                            <button
                                type="button"
                                class="alg-eye"
                                id="togglePassword"
                                aria-label="Tampilkan password">
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                        @error('password')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Masuk sebagai Admin

                    </button>

                </form>


                <a
                    href="{{ route('home') }}"
                    class="alg-back">

                    <i class="bi bi-arrow-left"></i>

                    Kembali ke Website

                </a>

            </div>

        </section>

    </main>

    <script>
        (function () {
            var input = document.getElementById('password');
            var toggle = document.getElementById('togglePassword');
            if (!input || !toggle) return;

            toggle.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                toggle.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            });
        })();
    </script>

</body>

</html>