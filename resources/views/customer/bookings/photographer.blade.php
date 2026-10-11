@extends('layouts.booking')

@section('title', 'Pilih Fotografer — Aura Studio')

@php
    $currentStep = 2;
@endphp

@section('content')

<section class="booking-page">

    <div class="booking-page-heading booking-page-heading-centered">
        <span class="booking-eyebrow">
            LANGKAH KEDUA
        </span>

        <h1>
            Pilih fotografer
            <em>untuk sesimu.</em>
        </h1>

        <p>
            Setiap fotografer Aura Studio memiliki karakter visual
            dan pendekatan yang berbeda. Pilih yang paling sesuai
            atau biarkan kami menentukan untukmu.
        </p>
    </div>

    @if ($errors->any())
        <div class="booking-alert booking-alert-error">

            <i class="bi bi-exclamation-circle"></i>

            <div>
                <strong>Periksa kembali pilihanmu.</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    @endif


    <form
        action="{{ route('customer.bookings.photographer.store') }}"
        method="POST"
        id="photographerForm"
    >
        @csrf

        <input
            type="hidden"
            name="photographer_id"
            id="photographer_id"
            value="{{ $selectedPhotographerId }}"
        >


        <div class="booking-step-layout">

            <div>

                {{-- AUTO --}}
                <button
                    type="button"
                    class="photographer-auto-card photographer-choice
                    {{ $selectedPhotographerId === 'auto' ? 'selected' : '' }}"
                    data-photographer-id="auto"
                >

                    <span class="photographer-auto-icon">
                        <i class="bi bi-stars"></i>
                    </span>

                    <span class="photographer-auto-copy">
                        <span class="photographer-auto-label">
                            DIREKOMENDASIKAN
                        </span>

                        <strong>
                            Pilihkan Fotografer untuk Saya
                        </strong>

                        <small>
                            Biarkan Aura Studio memilih fotografer
                            yang tersedia dan paling sesuai dengan
                            jadwal sesi pilihanmu.
                        </small>
                    </span>

                    <span class="photographer-select-mark">
                        <i class="bi bi-check-lg"></i>
                    </span>

                </button>


                <div class="photographer-divider">
                    <span>ATAU PILIH FOTOGRAFER</span>
                </div>


                @if ($photographers->isNotEmpty())

                    <div class="photographer-grid">

                        @foreach ($photographers as $photographer)

                            <button
                                type="button"
                                class="photographer-card photographer-choice
                                {{ (string) $selectedPhotographerId === (string) $photographer->id ? 'selected' : '' }}"
                                data-photographer-id="{{ $photographer->id }}"
                            >

                                <div class="photographer-image">

                                    @if ($photographer->photo)
                                        <img
                                            src="{{ asset('storage/' . $photographer->photo) }}"
                                            alt="{{ $photographer->name }}"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="photographer-placeholder">
                                            <span>
                                                {{ strtoupper(substr($photographer->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif

                                    <span class="photographer-select-mark">
                                        <i class="bi bi-check-lg"></i>
                                    </span>

                                </div>

                                <div class="photographer-card-body">

                                    <span class="photographer-specialization">
                                        {{ $photographer->specialization ?: 'Fotografer Aura Studio' }}
                                    </span>

                                    <h3>
                                        {{ $photographer->name }}
                                    </h3>

                                    @if ($photographer->description)
                                        <p>
                                            {{ \Illuminate\Support\Str::limit(
                                                $photographer->description,
                                                120
                                            ) }}
                                        </p>
                                    @else
                                        <p>
                                            Siap membantu mengabadikan
                                            momenmu dengan pendekatan visual
                                            khas Aura Studio.
                                        </p>
                                    @endif

                                    <span class="photographer-branch">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $branch->name }}
                                    </span>

                                </div>

                            </button>

                        @endforeach

                    </div>

                @else

                    <div class="booking-empty-state">
                        <i class="bi bi-person-x"></i>

                        <p>
                            Belum ada fotografer aktif pada cabang ini.
                            Kamu tetap dapat memilih opsi otomatis dan
                            sistem akan menyesuaikan dengan jadwal
                            yang tersedia.
                        </p>
                    </div>

                @endif

            </div>


            {{-- SUMMARY --}}
            <aside class="booking-summary">

                <div class="booking-summary-card">

                    <span class="booking-summary-eyebrow">
                        RESERVASI KAMU
                    </span>

                    <h3>Ringkasan</h3>

                    <div class="booking-summary-list">

                        <div class="summary-row">
                            <span>Layanan</span>
                            <strong>
                                {{ $service->name }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Cabang</span>
                            <strong>
                                {{ $branch->name }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Paket</span>
                            <strong>
                                {{ $package->name }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Durasi</span>
                            <strong>
                                {{ $package->duration_minutes }} menit
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Fotografer</span>
                            <strong id="summaryPhotographer">
                                Pilihkan untuk saya
                            </strong>
                        </div>

                    </div>

                    <div class="summary-total">

                        <span>Total Paket</span>

                        <strong>
                            Rp {{ number_format(
                                $package->price,
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>

                    </div>

                    <button
                        type="submit"
                        class="booking-next-button"
                    >
                        Lanjut Pilih Jadwal
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <a
                        href="{{ route(
                            'customer.bookings.create',
                            $package
                        ) }}"
                        class="booking-back-link"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Pilihan
                    </a>

                </div>

            </aside>

        </div>

    </form>

</section>

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input =
        document.getElementById('photographer_id');

    const choices = [
        ...document.querySelectorAll('.photographer-choice')
    ];

    const summary =
        document.getElementById('summaryPhotographer');


    function updateSummary() {

        const selected = choices.find(
            item =>
                item.dataset.photographerId === input.value
        );

        if (!selected || input.value === 'auto') {
            summary.textContent = 'Pilihkan untuk saya';
            return;
        }

        const name =
            selected.querySelector('h3');

        summary.textContent =
            name ? name.textContent.trim() : 'Fotografer dipilih';
    }


    choices.forEach(function (choice) {

        choice.addEventListener('click', function () {

            choices.forEach(function (item) {
                item.classList.remove('selected');
            });

            choice.classList.add('selected');

            input.value =
                choice.dataset.photographerId;

            updateSummary();

        });

    });


    updateSummary();

});
</script>

@endpush