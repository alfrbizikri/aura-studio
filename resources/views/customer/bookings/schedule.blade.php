@extends('layouts.booking')

@section('title', 'Pilih Jadwal — Aura Studio')

@php
    $currentStep = 3;

    $initialSchedule = $selectedScheduleId
        ? $schedules->firstWhere(
            'id',
            (int) $selectedScheduleId
        )
        : null;

    $initialDate = $initialSchedule
        ? $initialSchedule->schedule_date->format('Y-m-d')
        : ($schedules->first()?->schedule_date->format('Y-m-d'));
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
            Pilih jadwal yang tersedia sesuai cabang,
            paket, dan fotografer pilihanmu.
        </p>
    </div>


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


    <div class="schedule-context-card">

        <div class="schedule-context-icon">
            <i class="bi bi-calendar-check"></i>
        </div>

        <div class="schedule-context-list">

            <span>
                Paket
                <strong>{{ $package->name }}</strong>
            </span>

            <span>
                Durasi
                <strong>
                    {{ $package->duration_minutes }} menit
                </strong>
            </span>

            <span>
                Cabang
                <strong>{{ $branch->name }}</strong>
            </span>

            <span>
                Fotografer
                <strong>
                    @if ($photographer)
                        {{ $photographer->name }}
                    @else
                        Ditentukan dari jadwal
                    @endif
                </strong>
            </span>

        </div>

        <a
            href="{{ route('customer.bookings.photographer') }}"
            class="schedule-change-link"
        >
            Ubah Pilihan
            <i class="bi bi-arrow-right"></i>
        </a>

    </div>


    <form
        action="{{ route('customer.bookings.schedule.store') }}"
        method="POST"
        id="scheduleForm"
    >
        @csrf

        <input
            type="hidden"
            name="schedule_id"
            id="schedule_id"
            value="{{ $selectedScheduleId }}"
        >


        <div class="booking-step-layout">

            <div>

                @if ($schedules->isNotEmpty())

                    <div class="schedule-picker-card">

                        {{-- DATE COLUMN --}}
                        <div class="schedule-date-column">

                            <span class="schedule-mini-eyebrow">
                                01
                            </span>

                            <h2>Pilih Tanggal</h2>

                            <p>
                                Hanya tanggal yang masih memiliki
                                sesi tersedia yang ditampilkan.
                            </p>

                            <div class="schedule-date-list">

                                @foreach ($scheduleGroups as $date => $items)

                                    @php
                                        $dateObject =
                                            $items->first()
                                                ->schedule_date;
                                    @endphp

                                    <button
                                        type="button"
                                        class="schedule-date-choice
                                        {{ $initialDate === $date ? 'selected' : '' }}"
                                        data-date="{{ $date }}"
                                    >

                                        <small>
                                            {{ $dateObject
                                                ->locale('id')
                                                ->translatedFormat('D') }}
                                        </small>

                                        <strong>
                                            {{ $dateObject->format('d') }}
                                        </strong>

                                        <span>
                                            {{ $dateObject
                                                ->locale('id')
                                                ->translatedFormat('M Y') }}
                                        </span>

                                        <em>
                                            {{ $items->count() }}
                                            sesi
                                        </em>

                                    </button>

                                @endforeach

                            </div>

                        </div>


                        {{-- SESSION COLUMN --}}
                        <div class="schedule-session-column">

                            <span class="schedule-mini-eyebrow">
                                02
                            </span>

                            <h2>Pilih Waktu</h2>

                            <p>
                                Pilih salah satu sesi yang sesuai
                                dengan kebutuhanmu.
                            </p>


                            @foreach ($scheduleGroups as $date => $items)

                                <div
                                    class="schedule-session-group"
                                    data-session-date="{{ $date }}"
                                    @if ($initialDate !== $date)
                                        hidden
                                    @endif
                                >

                                    <div class="schedule-selected-date">
                                        <i class="bi bi-calendar3"></i>

                                        {{ $items->first()
                                            ->schedule_date
                                            ->locale('id')
                                            ->translatedFormat(
                                                'l, d F Y'
                                            ) }}
                                    </div>


                                    <div class="schedule-session-grid">

                                        @foreach ($items as $schedule)

                                            <button
                                                type="button"
                                                class="schedule-session-choice
                                                {{ (string) $selectedScheduleId === (string) $schedule->id ? 'selected' : '' }}"
                                                data-schedule-id="{{ $schedule->id }}"
                                                data-date-label="{{ $schedule->schedule_date->locale('id')->translatedFormat('l, d F Y') }}"
                                                data-start="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}"
                                                data-end="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}"
                                                data-photographer="{{ $schedule->photographer?->name ?? 'Tidak diperlukan' }}"
                                                data-room="{{ $schedule->studioRoom?->name ?? 'Tidak diperlukan' }}"
                                            >

                                                <strong>
                                                    {{ \Carbon\Carbon::parse(
                                                        $schedule->start_time
                                                    )->format('H:i') }}
                                                </strong>

                                                <span>
                                                    –

                                                    {{ \Carbon\Carbon::parse(
                                                        $schedule->end_time
                                                    )->format('H:i') }}
                                                </span>

                                            </button>

                                        @endforeach

                                    </div>

                                </div>

                            @endforeach


                            <div
                                class="schedule-selection-info"
                                id="scheduleSelectionInfo"
                                hidden
                            >

                                <span class="schedule-selection-check">
                                    <i class="bi bi-check-lg"></i>
                                </span>

                                <div>
                                    <small>SESI DIPILIH</small>

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

                    <div class="booking-empty-state schedule-empty">

                        <i class="bi bi-calendar-x"></i>

                        <h3>
                            Belum ada jadwal tersedia
                        </h3>

                        <p>
                            Tidak ada jadwal yang sesuai dengan
                            cabang, fotografer, dan durasi paket
                            pilihanmu.
                        </p>

                        <a
                            href="{{ route(
                                'customer.bookings.photographer'
                            ) }}"
                            class="booking-back-link"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Kembali Pilih Fotografer
                        </a>

                    </div>

                @endif


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
                                secara manual. Ruangan sudah mengikuti
                                sesi yang tersedia di sistem Aura Studio.
                            </p>
                        </div>

                    </div>

                @endif

            </div>


            {{-- SUMMARY --}}
            <aside class="booking-summary">

                <div class="booking-summary-card">

                    <span class="booking-summary-eyebrow">
                        RINGKASAN BOOKING
                    </span>

                    <h3>Reservasimu</h3>

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
                                {{ $package->duration_minutes }}
                                menit
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Fotografer</span>
                            <strong id="summarySchedulePhotographer">
                                @if ($photographer)
                                    {{ $photographer->name }}
                                @else
                                    Menyesuaikan jadwal
                                @endif
                            </strong>
                        </div>

                    </div>


                    <div
                        class="schedule-summary-selected"
                        id="scheduleSummarySelected"
                        hidden
                    >

                        <small>JADWAL TERPILIH</small>

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
                        @if ($schedules->isEmpty())
                            disabled
                        @endif
                    >
                        Lanjut Isi Detail

                        <i class="bi bi-arrow-right"></i>
                    </button>


                    <a
                        href="{{ route(
                            'customer.bookings.photographer'
                        ) }}"
                        class="booking-back-link"
                    >
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
document.addEventListener('DOMContentLoaded', function () {

    const scheduleInput =
        document.getElementById('schedule_id');

    const dateChoices = [
        ...document.querySelectorAll(
            '.schedule-date-choice'
        )
    ];

    const sessionGroups = [
        ...document.querySelectorAll(
            '.schedule-session-group'
        )
    ];

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


    function showDate(date) {

        dateChoices.forEach(function (choice) {
            choice.classList.toggle(
                'selected',
                choice.dataset.date === date
            );
        });

        sessionGroups.forEach(function (group) {
            group.hidden =
                group.dataset.sessionDate !== date;
        });

    }


    function updateSelectedSchedule() {

        const selected = sessionChoices.find(
            item =>
                item.dataset.scheduleId ===
                scheduleInput.value
        );

        if (!selected) {

            if (selectionInfo) {
                selectionInfo.hidden = true;
            }

            if (summaryBox) {
                summaryBox.hidden = true;
            }

            return;
        }


        selectionInfo.hidden = false;
        summaryBox.hidden = false;


        selectedScheduleText.textContent =
            `${selected.dataset.dateLabel} • ` +
            `${selected.dataset.start}–${selected.dataset.end} WIB`;


        selectedScheduleMeta.textContent =
            `Fotografer: ${selected.dataset.photographer}`;


        summaryDate.textContent =
            selected.dataset.dateLabel;


        summaryTime.textContent =
            `${selected.dataset.start}–${selected.dataset.end} WIB`;


        if (summaryPhotographer) {
            summaryPhotographer.textContent =
                selected.dataset.photographer;
        }


        if (summaryRoom) {
            summaryRoom.textContent =
                selected.dataset.room;
        }

    }


    dateChoices.forEach(function (choice) {

        choice.addEventListener('click', function () {

            showDate(choice.dataset.date);

        });

    });


    sessionChoices.forEach(function (choice) {

        choice.addEventListener('click', function () {

            sessionChoices.forEach(function (item) {
                item.classList.remove('selected');
            });

            choice.classList.add('selected');

            scheduleInput.value =
                choice.dataset.scheduleId;

            updateSelectedSchedule();

        });

    });


    updateSelectedSchedule();

});
</script>

@endpush