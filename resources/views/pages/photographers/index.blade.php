@extends('layouts.app')

@section('title', 'Fotografer - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/photographers.css') }}">
@endpush

@section('content')

    @php
        $splitSpec = fn($s) => collect(preg_split('/\s*(?:&|,|\/|\bdan\b)\s*/iu', (string) $s))->map('trim')->filter()->values();
        $specs = $photographers->flatMap(fn($p) => $splitSpec($p->specialization))->unique()->sort()->values();
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="ph-hero has-ring">
        <div class="container">
            <nav class="svc-crumb is-center" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span><strong>Fotografer</strong>
            </nav>
            <span class="svc-pill">Tim profesional Aura Studio</span>
            <h1>Temukan fotografer <em>yang sesuai.</em></h1>
            <p class="svc-lead">Kenali fotografer Aura Studio berdasarkan cabang dan spesialisasi sebelum melanjutkan proses
                booking.</p>

            <ul class="ph-perks">
                <li><i class="bi bi-camera"></i>
                    <div><strong>Fotografer profesional</strong><span>Terlatih dengan gaya editorial</span></div>
                </li>
                <li><i class="bi bi-palette"></i>
                    <div><strong>Beragam spesialisasi</strong><span>Portrait hingga outdoor</span></div>
                </li>
                <li><i class="bi bi-buildings"></i>
                    <div><strong>Di berbagai
                            cabang</strong><span>{{ $branches->pluck('name')->map(fn($n) => trim(last(explode('—', $n))))->implode(', ') }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </section>


    {{-- ============ DAFTAR ============ --}}
    <section class="svc-section">
        <div class="container">

            <div class="ph-filter">
                <label class="ph-field ph-field-search">
                    <span>Cari fotografer</span>
                    <div><i class="bi bi-search"></i><input type="search" id="phSearch"
                            placeholder="Cari berdasarkan nama atau spesialisasi" autocomplete="off"></div>
                </label>
                <label class="ph-field">
                    <span>Cabang</span>
                    <select id="phBranch">
                        <option value="">Semua Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected((string) $selectedBranch === (string) $branch->id)>
                                {{ $branch->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="ph-field">
                    <span>Spesialisasi</span>
                    <select id="phSpec">
                        <option value="">Semua Spesialisasi</option>
                        @foreach ($specs as $spec)
                            <option value="{{ strtolower($spec) }}">{{ $spec }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="button" class="ph-reset" id="phReset"><i class="bi bi-arrow-counterclockwise"></i>
                    Reset</button>
            </div>
            <p class="ph-count">Menampilkan <strong id="phCount">{{ $photographers->count() }}</strong> fotografer</p>

            <div class="ph-grid" id="phGrid">
                @forelse ($photographers as $person)
                    <article class="ph-card" data-text="{{ strtolower($person->name . ' ' . $person->specialization) }}"
                        data-branch="{{ $person->branch_id }}"
                        data-specs="{{ $splitSpec($person->specialization)->map(fn($s) => strtolower($s))->implode('|') }}">

                        <div class="ph-card-photo">
                            {{-- 🖼 GAMBAR: foto fotografer → kolom `photo` di tabel photographers --}}
                            @if ($person->photo)
                                <img src="{{ asset('storage/' . $person->photo) }}" alt="{{ $person->name }}" loading="lazy">
                            @else
                                <div class="svc-ph tone-{{ ($loop->index % 3) + 1 }}"><i class="bi bi-person"></i></div>
                            @endif
                        </div>

                        <div class="ph-card-body">
                            <h2>{{ $person->name }}</h2>
                            @if ($person->specialization)
                                <span class="ph-spec"><i class="bi bi-camera"></i>{{ $person->specialization }}</span>
                            @endif
                            @if ($person->branch)
                                <span class="ph-branch"><i class="bi bi-geo-alt"></i>{{ $person->branch->name }}</span>
                            @endif
                            @if ($person->description)
                                <p>{{ \Illuminate\Support\Str::limit($person->description, 110) }}</p>
                            @endif
                            <a href="{{ route('photographers.show', $person->id) }}" class="btn btn-outline">Lihat Profil</a>
                        </div>
                    </article>
                @empty
                    <p class="svc-empty">Belum ada fotografer tersedia.</p>
                @endforelse
            </div>

            <p class="svc-empty" id="phEmpty" hidden>Tidak ada fotografer yang cocok dengan filter.</p>

            <p class="svc-note is-center">
                <i class="bi bi-info-circle"></i>
                <span><strong>Pemilihan fotografer saat booking.</strong> Fotografer dapat dipilih untuk layanan yang
                    memerlukan fotografer. Pilihan yang tersedia menyesuaikan cabang, layanan, tanggal, dan sesi waktu. Self
                    Photo Studio tidak memerlukan fotografer.</span>
            </p>

        </div>
    </section>


    {{-- ============ CTA ============ --}}
    <section class="svc-cta">
        <div class="container">
            <div class="svc-cta-box has-ring">
                <span class="section-eyebrow">Reservasi sesi fotografi</span>
                <h2>Sudah menemukan fotografer pilihan?</h2>
                <p>Lanjutkan booking untuk memilih layanan, paket, cabang, tanggal, dan sesi yang tersedia.</p>
                <div class="svc-actions is-center">
                    <a href="{{ route('packages.index') }}" class="btn btn-primary">Mulai Booking</a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-light">Lihat Layanan</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        (function () {
            var cards = document.querySelectorAll('.ph-card');
            var q = document.getElementById('phSearch'), b = document.getElementById('phBranch'), s = document.getElementById('phSpec');
            var count = document.getElementById('phCount'), empty = document.getElementById('phEmpty');

            function apply() {
                var text = (q.value || '').trim().toLowerCase(), n = 0;
                cards.forEach(function (c) {
                    var ok = (!text || c.dataset.text.indexOf(text) !== -1)
                        && (!b.value || c.dataset.branch === b.value)
                        && (!s.value || c.dataset.specs.split('|').indexOf(s.value) !== -1);
                    c.hidden = !ok;
                    if (ok) { n++; c.classList.add('in'); }
                });
                count.textContent = n;
                empty.hidden = n !== 0 || !cards.length;
            }

            [q, b, s].forEach(function (el) { el.addEventListener(el === q ? 'input' : 'change', apply); });
            document.getElementById('phReset').addEventListener('click', function () {
                q.value = ''; b.value = ''; s.value = ''; apply();
            });
            apply();
        })();
    </script>
@endpush