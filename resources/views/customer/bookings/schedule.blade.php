@extends('layouts.booking')

@section('title', 'Pilih Jadwal — Aura Studio')

@php
    $currentStep = 3;

    $selectedStartTime =
        old(
            'start_time',
            (
                ($draft['schedule_date'] ?? null)
                === $selectedDate
            )
            ? ($draft['start_time'] ?? null)
            : null
        );
@endphp


@section('content')

    <section class="booking-page">

        <div class="booking-page-heading">

            <span class="booking-eyebrow">
                LANGKAH KETIGA
            </span>

            <h1>
                Pilih tanggal dan
                <em>sesi fotografi.</em>
            </h1>

            <p>
                Pilih waktu yang tersedia berdasarkan
                jam operasional cabang dan durasi paketmu.
            </p>

        </div>


        {{-- VALIDATION ERROR --}}
        @if ($errors->any())

            <div class="booking-alert booking-alert-error">

                <i class="bi bi-exclamation-circle"></i>

                <div>

                    <strong>
                        Jadwal belum dapat dipilih.
                    </strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

        @endif


        {{-- SESSION ERROR --}}
        @if (session('error'))

            <div class="booking-alert booking-alert-error">

                <i class="bi bi-exclamation-circle"></i>

                <div>
                    <strong>
                        Jadwal tidak tersedia.
                    </strong>

                    <p>
                        {{ session('error') }}
                    </p>
                </div>

            </div>

        @endif


        {{-- =====================================================
        CONTEXT
        ====================================================== --}}
        <div class="schedule-context-card">

            <div class="schedule-context-icon">
                <i class="bi bi-calendar-check"></i>
            </div>


            <div class="schedule-context-list">

                <span>
                    Paket

                    <strong>
                        {{ $package->name }}
                    </strong>
                </span>


                <span>
                    Durasi

                    <strong>
                        {{ $package->duration_minutes }}
                        menit
                    </strong>
                </span>


                <span>
                    Cabang

                    <strong>
                        {{ $branch->name }}
                    </strong>
                </span>


                <span>
                    Jam Operasional

                    <strong>
                        {{ \Carbon\Carbon::parse(
        $branch->opening_time
    )->format('H:i') }}

                        –

                        {{ \Carbon\Carbon::parse(
        $branch->closing_time
    )->format('H:i') }}
                        WIB
                    </strong>
                </span>


                <span>
                    Fotografer

                    <strong>

                        @if ($photographer)

                            {{ $photographer->name }}

                        @elseif ($service->requires_photographer)

                            Dipilihkan otomatis

                        @else

                            Tidak diperlukan

                        @endif

                    </strong>
                </span>

            </div>


            <a href="{{ route(
        'customer.bookings.photographer'
    ) }}" class="schedule-change-link">
                Ubah Pilihan

                <i class="bi bi-arrow-right"></i>
            </a>

        </div>


        {{-- =====================================================
        FORM
        ====================================================== --}}
        <form action="{{ route(
        'customer.bookings.schedule.store'
    ) }}" method="POST" id="scheduleForm">

            @csrf


            {{-- SLOT YANG DIPILIH --}}
            <input type="hidden" name="schedule_date" id="schedule_date" value="{{ $selectedDate }}">

            <input type="hidden" name="start_time" id="start_time" value="{{ $selectedStartTime }}">


            <div class="booking-step-layout">

                <div>


                    {{-- =================================================
                    AVAILABLE SLOT
                    ================================================== --}}
                    @if ($availableSlots->isNotEmpty())

                                <div class="schedule-picker-card">


                                    {{-- DATE COLUMN --}}
                                    <div class="schedule-date-column">

                                        <span class="schedule-mini-eyebrow">
                                            01
                                        </span>

                                        <h2>
                                            Pilih Tanggal
                                        </h2>

                                        <p>
                                            Pilih tanggal reservasi hingga
                                            satu tahun ke depan.
                                        </p>


                                        <div class="booking-calendar">

                                            {{-- MONTH HEADER --}}
                                            <div class="booking-calendar-header">

                                                @if ($previousMonthDate)

                                                                            <a href="{{ route(
                                                        'customer.bookings.schedule',
                                                        [
                                                            'date' =>
                                                                $previousMonthDate
                                                        ]
                                                    ) }}" class="booking-calendar-nav" aria-label="Bulan sebelumnya">
                                                                                <i class="bi bi-chevron-left"></i>
                                                                            </a>

                                                @else

                                                    <span class="
                                                                    booking-calendar-nav
                                                                    disabled
                                                                ">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </span>

                                                @endif


                                                <strong>
                                                    {{ $displayMonth
                        ->locale('id')
                        ->translatedFormat(
                            'F Y'
                        ) }}
                                                </strong>


                                                @if ($nextMonthDate)

                                                                            <a href="{{ route(
                                                        'customer.bookings.schedule',
                                                        [
                                                            'date' =>
                                                                $nextMonthDate
                                                        ]
                                                    ) }}" class="booking-calendar-nav" aria-label="Bulan berikutnya">
                                                                                <i class="bi bi-chevron-right"></i>
                                                                            </a>

                                                @else

                                                    <span class="
                                                                    booking-calendar-nav
                                                                    disabled
                                                                ">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </span>

                                                @endif

                                            </div>


                                            {{-- WEEKDAY --}}
                                            <div class="booking-calendar-weekdays">

                                                <span>Sen</span>
                                                <span>Sel</span>
                                                <span>Rab</span>
                                                <span>Kam</span>
                                                <span>Jum</span>
                                                <span>Sab</span>
                                                <span>Min</span>

                                            </div>


                                            {{-- DAYS --}}
                                            <div class="booking-calendar-days">

                                                @foreach ($calendarDays as $day)

                                                    @php
                                                        $dayString =
                                                            $day->toDateString();

                                                        $isCurrentMonth =
                                                            $day->month
                                                            === $displayMonth->month
                                                            &&
                                                            $day->year
                                                            === $displayMonth->year;

                                                        $isOutsideRange =
                                                            $day->lt($minDate)
                                                            || $day->gt($maxDate);

                                                        $isDisabled =
                                                            !$isCurrentMonth
                                                            || $isOutsideRange;

                                                        $isSelected =
                                                            $dayString
                                                            === $selectedDate;
                                                    @endphp


                                                    @if ($isDisabled)

                                                        <span class="
                                                                            booking-calendar-day
                                                                            disabled
                                                                        ">
                                                            @if ($isCurrentMonth)
                                                                {{ $day->format('d') }}
                                                            @endif
                                                        </span>

                                                    @else

                                                                            <a href="{{ route(
                                                            'customer.bookings.schedule',
                                                            [
                                                                'date' =>
                                                                    $dayString
                                                            ]
                                                        ) }}" class="
                                                                                                booking-calendar-day

                                                                                                {{ $isSelected
                                                            ? 'selected'
                                                            : '' }}

                                                                                                {{ $day->isToday()
                                                            ? 'today'
                                                            : '' }}
                                                                                            ">

                                                                                {{ $day->format('d') }}

                                                                            </a>

                                                    @endif

                                                @endforeach

                                            </div>

                                        </div>


                                        <div class="booking-calendar-range">

                                            <i class="bi bi-calendar-range"></i>

                                            <span>
                                                Booking tersedia hingga
                                                {{ $maxDate
                        ->locale('id')
                        ->translatedFormat(
                            'd F Y'
                        ) }}.
                                            </span>

                                        </div>

                                    </div>


                                    {{-- =========================================
                                    SESSION COLUMN
                                    ========================================== --}}
                                    <div class="schedule-session-column">

                                        <span class="schedule-mini-eyebrow">
                                            02
                                        </span>

                                        <h2>
                                            Pilih Waktu
                                        </h2>

                                        <p>
                                            Waktu dibuat otomatis dari jam
                                            operasional studio dan durasi paket.
                                        </p>


                                        <div class="schedule-selected-date">

                                            <i class="bi bi-calendar3"></i>

                                            {{ $selectedDateObject
                                                ->locale('id')
                                                ->translatedFormat(
                                                'l, d F Y'
                                            ) }}

                                        </div>


                                        @if ($availableSlots->isNotEmpty())

                                            <div class="schedule-session-grid">

                                                @foreach ($availableSlots as $slot)

                                                                @php
                                                                    $isSelected =
                                                                        $selectedStartTime
                                                                        === $slot['start_time'];
                                                                @endphp


                                                                <button type="button" class="
                                                        schedule-session-choice
                                                        {{ $isSelected
                                                    ? 'selected'
                                                    : '' }}
                                                    " data-date="{{ $slot['date'] }}" data-date-label="{{ $slot['date_label'] }}"
                                                                    data-start="{{ $slot['start_time'] }}" data-end="{{ $slot['end_time'] }}"
                                                                    data-photographer="{{ $slot['photographer_name'] ?? 'Tidak diperlukan' }}"
                                                                    data-room="{{ $slot['studio_room_name'] ?? 'Tidak diperlukan' }}">

                                                                    <strong>
                                                                        {{ $slot['start_time'] }}
                                                                    </strong>

                                                                    <span>
                                                                        –
                                                                        {{ $slot['end_time'] }}
                                                                    </span>

                                                                </button>

                                                @endforeach

                                            </div>

                                        @else

                                            <div class="schedule-day-empty">

                                                <i class="bi bi-calendar-x"></i>

                                                <strong>
                                                    Tidak ada waktu tersedia
                                                </strong>

                                                <p>
                                                    Coba pilih tanggal lain
                                                    pada kalender.
                                                </p>

                                            </div>

                                        @endif


                                        {{-- SELECTED INFORMATION --}}
                                        <div class="schedule-selection-info" id="scheduleSelectionInfo" hidden>

                                            <span class="schedule-selection-check">
                                                <i class="bi bi-check-lg"></i>
                                            </span>


                                            <div>

                                                <small>
                                                    SESI DIPILIH
                                                </small>

                                                <strong id="selectedScheduleText">
                                                    —
                                                </strong>

                                                <p id="selectedScheduleMeta">
                                                    —
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                    @else

                                    {{-- =============================================
                                    EMPTY
                                    ============================================== --}}
                                    <div class="
                                                booking-empty-state
                                                schedule-empty
                                            ">

                                        <i class="bi bi-calendar-x"></i>

                                        <h3>
                                            Belum ada waktu tersedia
                                        </h3>

                                        <p>
                                            Tidak ditemukan waktu yang sesuai
                                            dengan jam operasional, durasi paket,
                                            dan resource yang tersedia.
                                        </p>


                                        <a href="{{ route(
                            'customer.bookings.photographer'
                        ) }}" class="booking-back-link">

                                            <i class="bi bi-arrow-left"></i>

                                            Kembali Pilih Fotografer

                                        </a>

                                    </div>

                    @endif


                    {{-- =================================================
                    ROOM INFORMATION
                    ================================================== --}}
                    @if ($service->requires_room)

                        <div class="schedule-room-info">

                            <span>
                                <i class="bi bi-door-open"></i>
                            </span>


                            <div>

                                <strong>
                                    Penentuan Ruangan Otomatis
                                </strong>

                                <p>
                                    Kamu tidak perlu memilih studio room
                                    secara manual. Sistem akan memilih
                                    ruangan aktif yang tidak sedang
                                    digunakan pada waktu tersebut.
                                </p>

                            </div>

                        </div>

                    @endif


                    {{-- AUTO PHOTOGRAPHER INFORMATION --}}
                    @if (
                            $service->requires_photographer
                            && !$photographer
                        )

                        <div class="schedule-room-info">

                            <span>
                                <i class="bi bi-person-check"></i>
                            </span>


                            <div>

                                <strong>
                                    Fotografer Dipilihkan Otomatis
                                </strong>

                                <p>
                                    Sistem akan menentukan fotografer aktif
                                    yang tersedia pada sesi yang kamu pilih.
                                </p>

                            </div>

                        </div>

                    @endif

                </div>



                {{-- =====================================================
                SUMMARY
                ====================================================== --}}
                <aside class="booking-summary">

                    <div class="booking-summary-card">

                        <span class="booking-summary-eyebrow">
                            RINGKASAN BOOKING
                        </span>


                        <h3>
                            Reservasimu
                        </h3>


                        <div class="booking-summary-list">

                            <div class="summary-row">

                                <span>
                                    Layanan
                                </span>

                                <strong>
                                    {{ $service->name }}
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Cabang
                                </span>

                                <strong>
                                    {{ $branch->name }}
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Paket
                                </span>

                                <strong>
                                    {{ $package->name }}
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Durasi
                                </span>

                                <strong>
                                    {{ $package->duration_minutes }}
                                    menit
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Fotografer
                                </span>

                                <strong id="summarySchedulePhotographer">

                                    @if ($photographer)

                                        {{ $photographer->name }}

                                    @elseif (
                                            $service->requires_photographer
                                        )

                                        Menyesuaikan sesi

                                    @else

                                        Tidak diperlukan

                                    @endif

                                </strong>

                            </div>

                        </div>


                        {{-- SELECTED SUMMARY --}}
                        <div class="schedule-summary-selected" id="scheduleSummarySelected" hidden>

                            <small>
                                JADWAL TERPILIH
                            </small>

                            <strong id="summaryScheduleDate">
                                —
                            </strong>

                            <span id="summaryScheduleTime">
                                —
                            </span>


                            @if ($service->requires_room)

                                <p>

                                    <i class="bi bi-door-open"></i>

                                    <span id="summaryScheduleRoom">
                                        Ruangan otomatis
                                    </span>

                                </p>

                            @endif

                        </div>


                        <div class="summary-total">

                            <span>
                                Total Paket
                            </span>

                            <strong>
                                Rp {{ number_format(
        $package->price,
        0,
        ',',
        '.'
    ) }}
                            </strong>

                        </div>


                        <button type="submit" class="booking-next-button" id="scheduleContinueButton" disabled>

                            Lanjut Isi Detail

                            <i class="bi bi-arrow-right"></i>

                        </button>


                        <a href="{{ route(
        'customer.bookings.photographer'
    ) }}" class="booking-back-link">

                            <i class="bi bi-arrow-left"></i>

                            Kembali Pilih Fotografer

                        </a>

                    </div>

                </aside>

            </div>

        </form>

    </section>

@endsection



@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const dateInput =
            document.getElementById(
                'schedule_date'
            );

        const startInput =
            document.getElementById(
                'start_time'
            );

        const continueButton =
            document.getElementById(
                'scheduleContinueButton'
            );

        const sessionChoices = [
            ...document.querySelectorAll(
                '.schedule-session-choice'
            )
        ];

        const selectionInfo =
            document.getElementById(
                'scheduleSelectionInfo'
            );

        const selectedScheduleText =
            document.getElementById(
                'selectedScheduleText'
            );

        const selectedScheduleMeta =
            document.getElementById(
                'selectedScheduleMeta'
            );

        const summaryBox =
            document.getElementById(
                'scheduleSummarySelected'
            );

        const summaryDate =
            document.getElementById(
                'summaryScheduleDate'
            );

        const summaryTime =
            document.getElementById(
                'summaryScheduleTime'
            );

        const summaryPhotographer =
            document.getElementById(
                'summarySchedulePhotographer'
            );

        const summaryRoom =
            document.getElementById(
                'summaryScheduleRoom'
            );


        function findSelectedSlot() {

            return sessionChoices.find(
                function (item) {

                    return (
                        item.dataset.date
                            === dateInput.value
                        &&
                        item.dataset.start
                            === startInput.value
                    );

                }
            );
        }


        function updateSelectedSchedule() {

            const selected =
                findSelectedSlot();


            if (!selected) {

                if (selectionInfo) {
                    selectionInfo.hidden = true;
                }

                if (summaryBox) {
                    summaryBox.hidden = true;
                }

                if (continueButton) {
                    continueButton.disabled = true;
                }

                return;
            }


            sessionChoices.forEach(
                function (item) {

                    item.classList.toggle(
                        'selected',
                        item === selected
                    );

                }
            );


            if (selectionInfo) {
                selectionInfo.hidden = false;
            }

            if (summaryBox) {
                summaryBox.hidden = false;
            }

            if (continueButton) {
                continueButton.disabled = false;
            }


            selectedScheduleText.textContent =
                `${selected.dataset.dateLabel} • `
                + `${selected.dataset.start}`
                + `–${selected.dataset.end} WIB`;


            let meta =
                `Fotografer: `
                + selected.dataset.photographer;

            if (
                selected.dataset.room
                !== 'Tidak diperlukan'
            ) {
                meta +=
                    ` • Ruangan: `
                    + selected.dataset.room;
            }

            selectedScheduleMeta.textContent =
                meta;


            summaryDate.textContent =
                selected.dataset.dateLabel;

            summaryTime.textContent =
                `${selected.dataset.start}`
                + `–${selected.dataset.end} WIB`;


            if (summaryPhotographer) {
                summaryPhotographer.textContent =
                    selected.dataset.photographer;
            }


            if (summaryRoom) {
                summaryRoom.textContent =
                    selected.dataset.room;
            }
        }


        sessionChoices.forEach(
            function (choice) {

                choice.addEventListener(
                    'click',
                    function () {

                        sessionChoices.forEach(
                            function (item) {

                                item.classList.remove(
                                    'selected'
                                );

                            }
                        );


                        choice.classList.add(
                            'selected'
                        );


                        dateInput.value =
                            choice.dataset.date;

                        startInput.value =
                            choice.dataset.start;


                        updateSelectedSchedule();

                    }
                );

            }
        );


        updateSelectedSchedule();

    }
);

</script>

@endpush