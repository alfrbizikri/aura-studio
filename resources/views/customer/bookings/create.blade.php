@extends('layouts.booking')

@section('title', 'Pilih Reservasi — Aura Studio')

@php
    $currentStep = 1;

    $initialServiceId = old(
        'service_id',
        $selectedServiceId
    );

    $initialBranchId = old(
        'branch_id',
        $selectedBranchId
    );

    $initialPackageId = old(
        'package_id',
        $selectedPackageId
    );
@endphp

@section('content')

<section class="booking-page">

    <div class="booking-page-heading">
        <span class="booking-eyebrow">
            LANGKAH PERTAMA
        </span>

        <h1>
            Pilih pengalaman fotografi
            <em>yang sesuai untukmu.</em>
        </h1>

        <p>
            Tentukan layanan, cabang, dan paket yang ingin
            kamu reservasi bersama Aura Studio.
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
        action="{{ route('customer.bookings.selection.store') }}"
        method="POST"
        id="bookingSelectionForm"
    >
        @csrf

        <input
            type="hidden"
            name="service_id"
            id="service_id"
            value="{{ $initialServiceId }}"
        >

        <input
            type="hidden"
            name="branch_id"
            id="branch_id"
            value="{{ $initialBranchId }}"
        >

        <input
            type="hidden"
            name="package_id"
            id="package_id"
            value="{{ $initialPackageId }}"
        >

        <div class="booking-layout">

            <div class="booking-content-column">

                {{-- =========================
                    01 LAYANAN
                ========================== --}}
                <section class="booking-section">

                    <div class="booking-section-heading">
                        <span class="booking-section-number">
                            01
                        </span>

                        <div>
                            <h2>Pilih Layanan</h2>
                            <p>
                                Pilih jenis pengalaman fotografi
                                yang kamu inginkan.
                            </p>
                        </div>
                    </div>

                    <div class="booking-option-grid service-grid">

                        @foreach ($services as $service)

                            <button
                                type="button"
                                class="booking-choice service-choice
                                {{ (string) $initialServiceId === (string) $service->id ? 'selected' : '' }}"
                                data-service-id="{{ $service->id }}"
                                data-service-name="{{ $service->name }}"
                            >

                                <span class="choice-icon">
                                    @if ($service->requires_room)
                                        <i class="bi bi-camera"></i>
                                    @elseif ($service->is_on_location)
                                        <i class="bi bi-geo-alt"></i>
                                    @else
                                        <i class="bi bi-stars"></i>
                                    @endif
                                </span>

                                <span class="choice-copy">
                                    <strong>
                                        {{ $service->name }}
                                    </strong>

                                    <small>
                                        {{ \Illuminate\Support\Str::limit(
                                            $service->description
                                            ?: 'Pengalaman fotografi profesional bersama Aura Studio.',
                                            95
                                        ) }}
                                    </small>
                                </span>

                                <span class="choice-check">
                                    <i class="bi bi-check-lg"></i>
                                </span>

                            </button>

                        @endforeach

                    </div>

                </section>


                {{-- =========================
                    02 CABANG
                ========================== --}}
                <section class="booking-section">

                    <div class="booking-section-heading">
                        <span class="booking-section-number">
                            02
                        </span>

                        <div>
                            <h2>Pilih Cabang</h2>

                            <p>
                                Cabang yang tersedia akan
                                menyesuaikan layanan pilihanmu.
                            </p>
                        </div>
                    </div>

                    <div class="booking-option-grid branch-grid">

                        @foreach ($branches as $branch)

                            @php
                                $serviceIds = $branch->services
                                    ->pluck('id')
                                    ->implode(',');
                            @endphp

                            <button
                                type="button"
                                class="booking-choice branch-choice
                                {{ (string) $initialBranchId === (string) $branch->id ? 'selected' : '' }}"
                                data-branch-id="{{ $branch->id }}"
                                data-branch-name="{{ $branch->name }}"
                                data-service-ids="{{ $serviceIds }}"
                            >

                                <span class="choice-icon">
                                    <i class="bi bi-building"></i>
                                </span>

                                <span class="choice-copy">

                                    <strong>
                                        {{ $branch->name }}
                                    </strong>

                                    <small>
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $branch->address }}
                                    </small>

                                    <small>
                                        <i class="bi bi-clock"></i>

                                        {{ \Carbon\Carbon::parse(
                                            $branch->opening_time
                                        )->format('H:i') }}

                                        –

                                        {{ \Carbon\Carbon::parse(
                                            $branch->closing_time
                                        )->format('H:i') }}
                                    </small>

                                </span>

                                <span class="choice-check">
                                    <i class="bi bi-check-lg"></i>
                                </span>

                            </button>

                        @endforeach

                    </div>

                    <div
                        class="booking-empty-state"
                        id="branchEmptyState"
                        hidden
                    >
                        <i class="bi bi-building-x"></i>

                        <p>
                            Belum ada cabang aktif untuk layanan ini.
                        </p>
                    </div>

                </section>


                {{-- =========================
                    03 PAKET
                ========================== --}}
                <section class="booking-section">

                    <div class="booking-section-heading">
                        <span class="booking-section-number">
                            03
                        </span>

                        <div>
                            <h2>Pilih Paket</h2>

                            <p>
                                Pilih paket sesuai kebutuhan
                                dan durasi sesi.
                            </p>
                        </div>
                    </div>

                    <div class="booking-package-list">

                        @foreach ($packages as $item)

                            <button
                                type="button"
                                class="booking-package-card package-choice
                                {{ (string) $initialPackageId === (string) $item->id ? 'selected' : '' }}"
                                data-package-id="{{ $item->id }}"
                                data-service-id="{{ $item->service_id }}"
                                data-package-name="{{ $item->name }}"
                                data-package-price="{{ (float) $item->price }}"
                                data-package-duration="{{ $item->duration_minutes }}"
                                data-package-people="{{ $item->max_people }}"
                            >

                                <div class="package-main">

                                    <span class="package-radio">
                                        <span></span>
                                    </span>

                                    <div class="package-copy">

                                        <strong>
                                            {{ $item->name }}
                                        </strong>

                                        @if ($item->description)
                                            <p>
                                                {{ \Illuminate\Support\Str::limit(
                                                    $item->description,
                                                    115
                                                ) }}
                                            </p>
                                        @endif

                                        <div class="package-meta">

                                            <span>
                                                <i class="bi bi-clock"></i>
                                                {{ $item->duration_minutes }} menit
                                            </span>

                                            @if ($item->max_people)
                                                <span>
                                                    <i class="bi bi-people"></i>
                                                    Maks. {{ $item->max_people }} orang
                                                </span>
                                            @endif

                                            @if ($item->photo_count)
                                                <span>
                                                    <i class="bi bi-images"></i>
                                                    {{ $item->photo_count }} foto
                                                </span>
                                            @endif

                                        </div>

                                    </div>

                                </div>

                                <div class="package-price">
                                    <small>Mulai dari</small>

                                    <strong>
                                        Rp {{ number_format(
                                            $item->price,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </strong>
                                </div>

                            </button>

                        @endforeach

                    </div>

                    <div
                        class="booking-empty-state"
                        id="packageEmptyState"
                        hidden
                    >
                        <i class="bi bi-box-seam"></i>

                        <p>
                            Belum ada paket aktif untuk layanan ini.
                        </p>
                    </div>

                </section>

            </div>


            {{-- =========================
                SUMMARY
            ========================== --}}
            <aside class="booking-summary">

                <div class="booking-summary-card">

                    <span class="booking-summary-eyebrow">
                        RINGKASAN PILIHAN
                    </span>

                    <h3>Reservasimu</h3>

                    <div class="booking-summary-list">

                        <div class="summary-row">
                            <span>Layanan</span>
                            <strong id="summaryService">
                                Belum dipilih
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Cabang</span>
                            <strong id="summaryBranch">
                                Belum dipilih
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Paket</span>
                            <strong id="summaryPackage">
                                Belum dipilih
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Durasi</span>
                            <strong id="summaryDuration">
                                —
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Kapasitas</span>
                            <strong id="summaryPeople">
                                —
                            </strong>
                        </div>

                    </div>

                    <div class="summary-total">

                        <span>Total Paket</span>

                        <strong id="summaryPrice">
                            Rp 0
                        </strong>

                    </div>

                    <button
                        type="submit"
                        class="booking-next-button"
                        id="continueButton"
                    >
                        Lanjut Pilih Fotografer

                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <p class="summary-note">
                        <i class="bi bi-shield-check"></i>
                        Pilihan masih dapat kamu ubah
                        sebelum konfirmasi akhir.
                    </p>

                </div>

            </aside>

        </div>

    </form>

</section>

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const serviceInput = document.getElementById('service_id');
    const branchInput = document.getElementById('branch_id');
    const packageInput = document.getElementById('package_id');

    const serviceChoices = [
        ...document.querySelectorAll('.service-choice')
    ];

    const branchChoices = [
        ...document.querySelectorAll('.branch-choice')
    ];

    const packageChoices = [
        ...document.querySelectorAll('.package-choice')
    ];

    const branchEmptyState =
        document.getElementById('branchEmptyState');

    const packageEmptyState =
        document.getElementById('packageEmptyState');

    const summaryService =
        document.getElementById('summaryService');

    const summaryBranch =
        document.getElementById('summaryBranch');

    const summaryPackage =
        document.getElementById('summaryPackage');

    const summaryDuration =
        document.getElementById('summaryDuration');

    const summaryPeople =
        document.getElementById('summaryPeople');

    const summaryPrice =
        document.getElementById('summaryPrice');


    function rupiah(value) {

        return new Intl.NumberFormat(
            'id-ID',
            {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }
        ).format(value || 0);

    }


    function selectedService() {

        return serviceChoices.find(
            item =>
                item.dataset.serviceId ===
                serviceInput.value
        );

    }


    function selectedBranch() {

        return branchChoices.find(
            item =>
                item.dataset.branchId ===
                branchInput.value
        );

    }


    function selectedPackage() {

        return packageChoices.find(
            item =>
                item.dataset.packageId ===
                packageInput.value
        );

    }


    function filterBranches(resetInvalid = true) {

        const serviceId = serviceInput.value;

        let visibleCount = 0;

        branchChoices.forEach(function (item) {

            const ids =
                (item.dataset.serviceIds || '')
                .split(',')
                .filter(Boolean);

            const visible =
                serviceId &&
                ids.includes(serviceId);

            item.hidden = !visible;

            if (visible) {
                visibleCount++;
            }

        });

        branchEmptyState.hidden =
            visibleCount !== 0;

        if (resetInvalid && branchInput.value) {

            const current = selectedBranch();

            if (!current || current.hidden) {

                branchInput.value = '';

                branchChoices.forEach(
                    item => item.classList.remove('selected')
                );

            }

        }

    }


    function filterPackages(resetInvalid = true) {

        const serviceId = serviceInput.value;

        let visibleCount = 0;

        packageChoices.forEach(function (item) {

            const visible =
                item.dataset.serviceId === serviceId;

            item.hidden = !visible;

            if (visible) {
                visibleCount++;
            }

        });

        packageEmptyState.hidden =
            visibleCount !== 0;

        if (resetInvalid && packageInput.value) {

            const current = selectedPackage();

            if (!current || current.hidden) {

                packageInput.value = '';

                packageChoices.forEach(
                    item => item.classList.remove('selected')
                );

            }

        }

    }


    function updateSummary() {

        const service = selectedService();
        const branch = selectedBranch();
        const packageItem = selectedPackage();

        summaryService.textContent =
            service
                ? service.dataset.serviceName
                : 'Belum dipilih';

        summaryBranch.textContent =
            branch
                ? branch.dataset.branchName
                : 'Belum dipilih';

        summaryPackage.textContent =
            packageItem
                ? packageItem.dataset.packageName
                : 'Belum dipilih';

        summaryDuration.textContent =
            packageItem
                ? `${packageItem.dataset.packageDuration} menit`
                : '—';

        summaryPeople.textContent =
            packageItem &&
            packageItem.dataset.packagePeople
                ? `Maks. ${packageItem.dataset.packagePeople} orang`
                : '—';

        summaryPrice.textContent =
            packageItem
                ? rupiah(packageItem.dataset.packagePrice)
                : rupiah(0);

    }


    serviceChoices.forEach(function (item) {

        item.addEventListener('click', function () {

            serviceChoices.forEach(
                choice =>
                    choice.classList.remove('selected')
            );

            item.classList.add('selected');

            serviceInput.value =
                item.dataset.serviceId;

            branchInput.value = '';
            packageInput.value = '';

            branchChoices.forEach(
                choice =>
                    choice.classList.remove('selected')
            );

            packageChoices.forEach(
                choice =>
                    choice.classList.remove('selected')
            );

            filterBranches();
            filterPackages();
            updateSummary();

        });

    });


    branchChoices.forEach(function (item) {

        item.addEventListener('click', function () {

            if (item.hidden) {
                return;
            }

            branchChoices.forEach(
                choice =>
                    choice.classList.remove('selected')
            );

            item.classList.add('selected');

            branchInput.value =
                item.dataset.branchId;

            updateSummary();

        });

    });


    packageChoices.forEach(function (item) {

        item.addEventListener('click', function () {

            if (item.hidden) {
                return;
            }

            packageChoices.forEach(
                choice =>
                    choice.classList.remove('selected')
            );

            item.classList.add('selected');

            packageInput.value =
                item.dataset.packageId;

            updateSummary();

        });

    });


    filterBranches(false);
    filterPackages(false);
    updateSummary();

});
</script>

@endpush