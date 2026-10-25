
@extends('layouts.admin')

@section('title', 'Manajemen Ulasan - Aura Studio')

@push('styles')
<style>
    .review-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 18px 26px;
        border-bottom: 1px solid var(--a-line);
    }

    .review-comment {
        min-width: 240px;
        max-width: 380px;
        line-height: 1.7;
        overflow-wrap: anywhere;
        white-space: normal;
    }

    .review-stars {
        color: var(--a-gold);
        font-size: 1rem;
        letter-spacing: 2px;
        white-space: nowrap;
    }

    .review-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .review-actions form {
        margin: 0;
    }

    .review-filter-active {
        background: var(--a-ink);
        color: #fff;
        border-color: var(--a-ink);
    }

    @media (max-width: 600px) {
        .review-filters {
            padding: 16px;
        }

        .review-comment {
            min-width: 200px;
        }
    }
</style>
@endpush

@section('content')

    {{-- HEADER --}}
    <div class="admin-page-header">
        <div>
            <span class="admin-label">PENGALAMAN PELANGGAN</span>

            <h1>Manajemen Ulasan</h1>

            <p>
                Periksa rating dan komentar pelanggan sebelum
                ditampilkan di website Aura Studio.
            </p>
        </div>
    </div>

    {{-- PESAN BERHASIL --}}
    @if (session('success'))
        <div class="admin-alert success" role="status">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- PESAN ERROR --}}
    @if (session('error'))
        <div class="admin-alert error" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="admin-stats">
        <div class="admin-stat-card">
            <span>Total Ulasan</span>
            <strong>{{ $counts['all'] }}</strong>
        </div>

        <div class="admin-stat-card">
            <span>Menunggu Persetujuan</span>
            <strong>{{ $counts['pending'] }}</strong>
        </div>

        <div class="admin-stat-card">
            <span>Dipublikasikan</span>
            <strong>{{ $counts['published'] }}</strong>
        </div>

        <div class="admin-stat-card">
            <span>Disembunyikan</span>
            <strong>{{ $counts['hidden'] }}</strong>
        </div>
    </div>

    {{-- TABEL ULASAN --}}
    <div class="admin-table-card">

        <div class="admin-table-title">
            <h2>Daftar Rating dan Komentar</h2>

            <p>
                Gunakan filter status untuk memudahkan pemeriksaan ulasan.
            </p>
        </div>

        {{-- FILTER STATUS --}}
        <div class="review-filters">
            @php
                $filters = [
                    'all' => 'Semua',
                    'pending' => 'Menunggu',
                    'published' => 'Dipublikasikan',
                    'hidden' => 'Disembunyikan',
                ];
            @endphp

            @foreach ($filters as $key => $label)
                <a
                    href="{{ route('admin.testimonials.index', ['status' => $key]) }}"
                    class="admin-btn-secondary {{ $status === $key ? 'review-filter-active' : '' }}"
                    @if ($status === $key) aria-current="page" @endif
                >
                    {{ $label }}
                    <span>
                        ({{ $counts[$key] ?? $counts['all'] }})
                    </span>
                </a>
            @endforeach
        </div>

        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Pelanggan</th>
                        <th>Kode Booking</th>
                        <th>Paket</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            {{-- NOMOR --}}
                            <td>
                                {{ $testimonials->firstItem() + $loop->index }}
                            </td>

                            {{-- PELANGGAN --}}
                            <td>
                                <strong>
                                    {{ $testimonial->booking?->user?->name ?? '-' }}
                                </strong>
                            </td>

                            {{-- BOOKING --}}
                            <td>
                                {{ $testimonial->booking?->booking_code ?? '-' }}
                            </td>

                            {{-- PAKET --}}
                            <td>
                                {{ $testimonial->booking?->package?->name ?? '-' }}
                            </td>

                            {{-- RATING --}}
                            <td>
                                <div
                                    class="review-stars"
                                    aria-label="Rating {{ $testimonial->rating }} dari 5"
                                >
                                    @for ($star = 1; $star <= 5; $star++)
                                        {{ $star <= $testimonial->rating ? '★' : '☆' }}
                                    @endfor
                                </div>

                                <small>
                                    {{ $testimonial->rating }}/5
                                </small>
                            </td>

                            {{-- KOMENTAR --}}
                            <td>
                                <div class="review-comment">
                                    {{ $testimonial->comment }}
                                </div>
                            </td>

                            {{-- TANGGAL --}}
                            <td>
                                {{ $testimonial->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if ($testimonial->status === 'pending')
                                    <span class="admin-status">
                                        Menunggu Persetujuan
                                    </span>
                                @elseif ($testimonial->status === 'published')
                                    <span class="admin-status active">
                                        Dipublikasikan
                                    </span>
                                @else
                                    <span class="admin-status inactive">
                                        Disembunyikan
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="review-actions">

                                    {{-- SETUJUI / PUBLIKASIKAN --}}
                                    @if ($testimonial->status !== 'published')
                                        <form
                                            action="{{ route('admin.testimonials.publish', $testimonial) }}"
                                            method="POST"
                                            onsubmit="return confirm('Publikasikan ulasan ini?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="admin-action-btn edit"
                                                title="Setujui dan publikasikan"
                                            >
                                                <i class="bi bi-check-circle"></i>
                                                Setujui
                                            </button>
                                        </form>
                                    @endif

                                    {{-- SEMBUNYIKAN --}}
                                    @if ($testimonial->status !== 'hidden')
                                        <form
                                            action="{{ route('admin.testimonials.hide', $testimonial) }}"
                                            method="POST"
                                            onsubmit="return confirm('Sembunyikan ulasan ini dari publik?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="admin-action-btn delete"
                                                title="Sembunyikan ulasan"
                                            >
                                                <i class="bi bi-eye-slash"></i>
                                                Sembunyikan
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="admin-empty">
                                Belum ada ulasan pada kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($testimonials->hasPages())
            <div style="padding: 20px 26px;">
                {{ $testimonials->links() }}
            </div>
        @endif

    </div>

@endsection
