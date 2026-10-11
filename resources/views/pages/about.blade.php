@extends('layouts.app')

@section('title', 'Tentang Kami - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

    @php
        /* ==========================================================
           🖼 SLOT GAMBAR HALAMAN INI
           Isi dengan path relatif ke folder public/ (contoh: 'images/about/hero-1.jpg').
           Biarkan null untuk memakai placeholder.
           ========================================================== */
        $img = [
            'hero_main' => null,   // foto besar di hero (studio / set kamera)
            'hero_side' => null,   // foto lebar di bawah foto hero (suasana / pelanggan)
            'story' => null,   // foto di bagian "Cerita Kami"
        ];
        // Foto tiap cabang di bagian "Temukan Aura Studio": kolom `image` di tabel branches

        /* Kontak — ubah di sini bila berubah */
        $contact = [
            'whatsapp' => '+62 812-3456-7890',
            'email' => 'halo@aurastudio.id',
            'hours' => 'Senin – Minggu, 09.00 – 21.00 WIB',
            'area' => $branches->pluck('name')->implode(' • '),
        ];
        $waNumber = preg_replace('/\D+/', '', $contact['whatsapp']);

        $values = [
            ['bi-heart', 'Hangat dan personal', 'Komunikasi yang ramah serta arahan sesi yang mudah dipahami.'],
            ['bi-brightness-high', 'Visual yang terarah', 'Pencahayaan, komposisi, dan konsep disiapkan sesuai kebutuhan sesi.'],
            ['bi-sliders', 'Pilihan yang fleksibel', 'Tersedia layanan studio, sesi mandiri, dan fotografi di lokasi pelanggan.'],
            ['bi-calendar-check', 'Reservasi terstruktur', 'Cabang, paket, fotografer, tanggal, dan sesi dipilih melalui alur yang jelas.'],
        ];

        $reasons = [
            ['Tiga pilihan layanan', 'Photo Studio, Self Photo Studio, dan Fotografi On Location.'],
            ['Cabang di beberapa kota', 'Tersedia di ' . ($branches->count() ? $branches->count() . ' cabang aktif' : 'beberapa kota') . '.'],
            ['Paket dengan durasi jelas', 'Durasi sesi, kapasitas, jumlah foto, dan fasilitas ditampilkan sebelum booking.'],
            ['Jadwal berdasarkan cabang', 'Tanggal dan sesi yang tersedia menyesuaikan cabang, layanan, ruangan, dan fotografer.'],
        ];

        $faqs = [
            ['Apakah semua cabang menyediakan layanan yang sama?', 'Tidak selalu. Layanan yang tersedia bisa berbeda di setiap cabang. Lihat halaman Cabang atau pilih layanan saat booking.'],
            ['Apakah saya dapat memilih fotografer?', 'Ya, untuk layanan yang memerlukan fotografer. Pilihan yang tampil menyesuaikan cabang, tanggal, dan sesi waktu.'],
            ['Apakah Self Photo Studio menggunakan fotografer?', 'Tidak. Sesi dilakukan secara mandiri di ruang studio privat.'],
            ['Bagaimana menentukan durasi sesi?', 'Durasi mengikuti paket yang kamu pilih. Detailnya tertera di halaman setiap paket.'],
            ['Kapan saya dapat memilih tanggal dan jam?', 'Setelah memilih layanan, paket, dan cabang di alur booking, kamu bisa memilih slot yang masih tersedia.'],
        ];
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="abt-hero has-ring">
        <div class="container">
            <div class="abt-hero-grid">

                <div class="abt-hero-copy">
                    <nav class="svc-crumb" aria-label="Breadcrumb">
                        <a href="{{ url('/') }}">Beranda</a><span class="sep">/</span><strong>Tentang Kami</strong>
                    </nav>
                    <span class="section-eyebrow">Mengenal Aura Studio</span>
                    <h1>Ruang untuk mengabadikan cerita yang <em>bermakna.</em></h1>
                    <p class="svc-lead">Aura Studio menghadirkan pengalaman fotografi yang hangat, terarah, dan mudah
                        diakses melalui layanan studio, self photo, dan fotografi di lokasi pilihan pelanggan.</p>
                    <div class="svc-actions">
                        <a href="{{ route('services.index') }}" class="btn btn-primary">Jelajahi Layanan</a>
                        <a href="#kontak" class="btn btn-outline">Hubungi Kami</a>
                    </div>
                </div>

                <div class="abt-collage">
                    <div class="abt-collage-main">
                        {{-- 🖼 GAMBAR: $img['hero_main'] --}}
                        @if ($img['hero_main'])
                            <img src="{{ asset($img['hero_main']) }}" alt="Studio Aura">
                        @else
                            <div class="svc-ph"><i class="bi bi-camera"></i><span>Aura Studio</span></div>
                        @endif
                    </div>
                    <div class="abt-collage-side">
                        {{-- 🖼 GAMBAR: $img['hero_side'] --}}
                        @if ($img['hero_side'])
                            <img src="{{ asset($img['hero_side']) }}" alt="Suasana sesi foto">
                        @else
                            <div class="svc-ph tone-2"><i class="bi bi-images"></i></div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ============ CERITA ============ --}}
    <section class="svc-section">
        <div class="container">
            <div class="abt-story">

                <div class="abt-story-img">
                    {{-- 🖼 GAMBAR: $img['story'] --}}
                    @if ($img['story'])
                        <img src="{{ asset($img['story']) }}" alt="Cerita Aura Studio" loading="lazy">
                    @else
                        <div class="svc-ph tone-3"><i class="bi bi-camera-reels"></i><span>Cerita kami</span></div>
                    @endif
                </div>

                <div class="abt-story-copy">
                    <span class="section-eyebrow">Cerita kami</span>
                    <h2>Fotografi yang terasa lebih personal</h2>
                    <p>Aura Studio dibangun sebagai ruang fotografi yang menggabungkan kenyamanan, estetika editorial, dan
                        proses reservasi yang terstruktur. Kami percaya bahwa pengalaman di depan kamera seharusnya terasa
                        tak kaku atau membingungkan.</p>
                    <p>Melalui pilihan layanan yang fleksibel, pelanggan dapat menikmati sesi bersama fotografer
                        profesional, berekspresi secara mandiri di Self Photo Studio, atau membawa pengalaman Aura Studio ke
                        lokasi pilihan mereka.</p>
                    <blockquote>“Setiap orang memiliki cerita yang layak diabadikan dengan cara yang autentik.”</blockquote>
                </div>

            </div>
        </div>
    </section>


    {{-- ============ NILAI ============ --}}
    <section class="svc-section is-alt">
        <div class="container">
            <div class="svc-head">
                <span class="section-eyebrow">Nilai Aura Studio</span>
                <h2>Pengalaman yang kami jaga</h2>
                <p>Setiap detail dirancang untuk membantu pelanggan merasa nyaman sejak memilih layanan hingga sesi selesai.
                </p>
            </div>

            <div class="abt-values">
                @foreach ($values as [$vIcon, $vTitle, $vText])
                    <article class="abt-value">
                        <span class="abt-value-ico"><i class="bi {{ $vIcon }}"></i></span>
                        <h3>{{ $vTitle }}</h3>
                        <p>{{ $vText }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ============ MENGAPA ============ --}}
    <section class="svc-section">
        <div class="container">
            <div class="abt-why">
                <div>
                    <span class="section-eyebrow">Mengapa Aura Studio</span>
                    <h2>Lebih dari sekadar tempat berfoto</h2>
                    <p>Kami merancang pengalaman fotografi yang dapat disesuaikan dengan jenis momen, lokasi, dan tingkat
                        kenyamanan setiap pelanggan.</p>
                </div>
                <ul class="abt-why-list">
                    @foreach ($reasons as [$rTitle, $rText])
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <div><strong>{{ $rTitle }}</strong><span>{{ $rText }}</span></div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>


    {{-- ============ CABANG ============ --}}
    @if ($branches->isNotEmpty())
        <section class="svc-section is-alt">
            <div class="container">
                <div class="svc-head is-left">
                    <span class="section-eyebrow">Hadir lebih dekat</span>
                    <h2>Temukan Aura Studio di kota anda</h2>
                    <p>Setiap cabang memiliki layanan dan jadwal yang dapat berbeda. Pilih cabang untuk melihat informasi
                        lengkapnya.</p>
                </div>

                <div class="svc-cards">
                    @foreach ($branches as $branch)
                        <article class="abt-branch">
                            <div class="abt-branch-img">
                                <div class="svc-ph tone-{{ ($loop->index % 3) + 1 }}">
                                    <i class="bi bi-building"></i>
                                </div>
                            </div>
                            <div class="abt-branch-body">
                                <h3>{{ $branch->name }}</h3>
                                <p><i class="bi bi-geo-alt"></i>{{ \Illuminate\Support\Str::limit($branch->address, 70) }}</p>
                                @if ($branch->services->isNotEmpty())
                                    <small>{{ $branch->services->pluck('name')->implode(' • ') }}</small>
                                @endif
                                <a href="{{ route('branches.show', $branch->id) }}" class="btn btn-outline">Lihat Detail Cabang</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ============ KONTAK ============ --}}
    <section class="svc-section" id="kontak">
        <div class="container">
            <div class="abt-contact">

                <div class="abt-contact-info has-ring">
                    <span class="section-eyebrow">Kontak &amp; reservasi</span>
                    <h2>Kami siap membantu</h2>
                    <p>Hubungi tim Aura Studio untuk pertanyaan mengenai layanan, paket, atau proses booking.</p>
                    <ul>
                        <li><i class="bi bi-whatsapp"></i>
                            <div><span>WhatsApp</span><strong>{{ $contact['whatsapp'] }}</strong></div>
                        </li>
                        <li><i class="bi bi-envelope"></i>
                            <div><span>Email</span><strong>{{ $contact['email'] }}</strong></div>
                        </li>
                        <li><i class="bi bi-clock"></i>
                            <div><span>Jam operasional</span><strong>{{ $contact['hours'] }}</strong></div>
                        </li>
                        <li><i class="bi bi-geo-alt"></i>
                            <div><span>Lokasi</span><strong>{{ $contact['area'] }}</strong></div>
                        </li>
                    </ul>
                </div>

                <div class="abt-contact-ways">
                    <h3>Pilih cara menghubungi kami</h3>

                    <div class="abt-way">
                        <div>
                            <strong>WhatsApp</strong>
                            <span>Dapatkan bantuan cepat dari tim reservasi Aura Studio.</span>
                        </div>
                        <a href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode('Halo Aura Studio, saya ingin bertanya tentang layanan.') }}"
                            target="_blank" rel="noopener" class="btn btn-primary"><i class="bi bi-whatsapp"></i> Chat via
                            WhatsApp</a>
                    </div>

                    <div class="abt-way">
                        <div>
                            <strong>Email</strong>
                            <span>Kirimkan pertanyaan atau kebutuhan kerja sama melalui email.</span>
                        </div>
                        <a href="mailto:{{ $contact['email'] }}" class="btn btn-outline"><i class="bi bi-envelope"></i>
                            Kirim Email</a>
                    </div>

                    <p class="svc-note">
                        <i class="bi bi-info-circle"></i>
                        <span>Untuk membuat reservasi, gunakan tombol Booking Sekarang agar pilihan cabang, paket, dan
                            jadwal tercatat dalam sistem.</span>
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- ============ FAQ ============ --}}
    <section class="svc-section is-alt">
        <div class="container">
            <div class="svc-head">
                <span class="section-eyebrow">Informasi umum</span>
                <h2>Pertanyaan yang sering diajukan</h2>
            </div>
            <div class="svc-faq">
                @foreach ($faqs as [$q, $a])
                    <details>
                        <summary>{{ $q }}<i class="bi bi-chevron-down"></i></summary>
                        <div class="svc-faq-body">{{ $a }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ============ CTA ============ --}}
    <section class="svc-cta">
        <div class="container">
            <div class="svc-cta-box has-ring">
                <span class="section-eyebrow">Mulai pengalamanmu</span>
                <h2>Siap mengabadikan momen bersama Aura Studio?</h2>
                <p>Pilih layanan, cabang, paket, dan jadwal yang sesuai dengan kebutuhanmu.</p>
                <div class="svc-actions is-center">
                    <a href="{{ route('packages.index') }}" class="btn btn-primary">Mulai Booking</a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-light">Lihat Layanan</a>
                </div>
            </div>
        </div>
    </section>

@endsection