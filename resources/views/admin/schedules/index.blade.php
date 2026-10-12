@extends('layouts.admin')

@section('title', 'Kelola Jadwal - Aura Studio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-schedules.css') }}">
@endpush

@section('content')

    <div class="admin-page-header schedule-page-header">

        <div>
            <span class="admin-label">
                OPERASIONAL
            </span>

            <h1>Kelola Jadwal</h1>

            <p>
                Pantau ketersediaan layanan berdasarkan
                cabang, tanggal, dan jam operasional studio.
            </p>
        </div>

    </div>


    {{-- ALERT --}}
    @if (session('success'))
        <div class="admin-alert success">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="admin-alert error">
            <i class="bi bi-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- ======================================================
    FILTER BAR
    ====================================================== --}}
    <div class="schedule-filter-card">

        <form action="{{ route('admin.schedules.index') }}" method="GET" class="schedule-filter-form"
            id="scheduleFilterForm">

            <div class="schedule-filter-group">

                <label for="branch">
                    Cabang
                </label>

                <select name="branch" id="branch" onchange="this.form.submit()">
                    @foreach ($branches as $branch)

                        <option value="{{ $branch->id }}" @selected(
                            $selectedBranch->id === $branch->id
                        )>
                            {{ $branch->name }}
                        </option>

                    @endforeach
                </select>

            </div>


            <div class="schedule-filter-group">

                <label for="service">
                    Layanan
                </label>

                <select name="service" id="service" onchange="this.form.submit()">

                    <option value="">
                        Semua Layanan
                    </option>

                    @foreach ($services as $service)

                        <option value="{{ $service->id }}" @selected(
                            (string) $selectedServiceId
                            === (string) $service->id
                        )>
                            {{ $service->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <input type="hidden" name="week" value="{{ $weekStart->toDateString() }}">


            <div class="schedule-operating-info">

                <span class="schedule-operating-icon">
                    <i class="bi bi-clock"></i>
                </span>

                <div>
                    <small>
                        JAM OPERASIONAL
                    </small>

                    <strong>
                        {{ $openingTime->format('H:i') }}
                        –
                        {{ $closingTime->format('H:i') }}
                        WIB
                    </strong>
                </div>

            </div>

        </form>

    </div>


    {{-- ======================================================
    STATS
    ====================================================== --}}
    <div class="schedule-stats">

        <div class="schedule-stat">

            <span class="schedule-stat-icon total">
                <i class="bi bi-calendar3"></i>
            </span>

            <div>
                <small>Jadwal Minggu Ini</small>
                <strong>{{ $stats['total'] }}</strong>
            </div>

        </div>



        <div class="schedule-stat">

            <span class="schedule-stat-icon booked">
                <i class="bi bi-camera"></i>
            </span>

            <div>
                <small>Terbooking</small>
                <strong>{{ $stats['booked'] }}</strong>
            </div>

        </div>


        <div class="schedule-stat">

            <span class="schedule-stat-icon blocked">
                <i class="bi bi-slash-circle"></i>
            </span>

            <div>
                <small>Diblokir</small>
                <strong>{{ $stats['blocked'] }}</strong>
            </div>

        </div>


        <div class="schedule-stat">

            <span class="schedule-stat-icon past">
                <i class="bi bi-clock-history"></i>
            </span>

            <div>
                <small>Sudah Lewat</small>
                <strong>{{ $stats['past'] }}</strong>
            </div>

        </div>

    </div>


    {{-- ======================================================
    WEEK NAVIGATION
    ====================================================== --}}
    <div class="schedule-board-card">

        <div class="schedule-board-toolbar">

            <div>

                <span class="admin-label">
                    SCHEDULE BOARD
                </span>

                <h2>
                    {{ $weekStart
        ->locale('id')
        ->translatedFormat('d M') }}

                    –

                    {{ $weekEnd
        ->locale('id')
        ->translatedFormat('d M Y') }}
                </h2>

                <p>
                    {{ $selectedBranch->name }}
                </p>

            </div>


            <div class="schedule-week-nav">

                <a href="{{ route('admin.schedules.index', [
        'branch' => $selectedBranch->id,
        'service' => $selectedServiceId,
        'week' => $previousWeek,
    ]) }}" class="schedule-nav-button" title="Minggu sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>


                <a href="{{ route('admin.schedules.index', [
        'branch' => $selectedBranch->id,
        'service' => $selectedServiceId,
        'week' => $currentWeek,
    ]) }}" class="schedule-today-button">
                    <i class="bi bi-calendar-event"></i>
                    Minggu Ini
                </a>


                <a href="{{ route('admin.schedules.index', [
        'branch' => $selectedBranch->id,
        'service' => $selectedServiceId,
        'week' => $nextWeek,
    ]) }}" class="schedule-nav-button" title="Minggu berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>

            </div>

        </div>


        {{-- LEGEND --}}
        <div class="schedule-legend">

            <span class="schedule-legend-note">
                Area kosong = tersedia
            </span>

            <span>
                <i class="legend-dot booked"></i>
                Terbooking
            </span>

            <span>
                <i class="legend-dot blocked"></i>
                Diblokir
            </span>

            <span>
                <i class="legend-dot past"></i>
                Sudah Lewat
            </span>

        </div>


        {{-- ==================================================
        BOARD
        =================================================== --}}
        @if ($timeSlots->isNotEmpty())

            <div class="schedule-board-scroll">

                <div class="schedule-board-grid" style="
                                                            --schedule-row-count:
                                                            {{ $timeSlots->count() }};
                                                        ">

                    {{-- TOP LEFT --}}
                    <div class="schedule-time-header">
                        Waktu
                    </div>


                    {{-- DAY HEADERS --}}
                    @foreach ($weekDays as $dayIndex => $day)

                            <div class="schedule-day-header
                                                                                                        {{ $day->isToday() ? 'today' : '' }}"
                                style="
                                                                                                            grid-column:
                                                                                                            {{ $dayIndex + 2 }};
                                                                                                            grid-row: 1;
                                                                                                        ">

                                <small>
                                    {{ strtoupper(
                            $day
                                ->locale('id')
                                ->translatedFormat('D')
                        ) }}
                                </small>

                                <strong>
                                    {{ $day->format('d') }}
                                </strong>

                                <span>
                                    {{ $day
                            ->locale('id')
                            ->translatedFormat('M') }}
                                </span>

                                @if ($day->isToday())
                                    <em>Hari ini</em>
                                @endif

                            </div>

                    @endforeach


                    {{-- TIME LABELS --}}
                    @foreach ($timeSlots as $slotIndex => $time)

                        <div class="schedule-time-label" style="
                                                                                        grid-column: 1;
                                                                                        grid-row:
                                                                                        {{ $slotIndex + 2 }};
                                                                                    ">
                            {{ $time }}
                        </div>

                    @endforeach


                    {{-- GRID BACKGROUND --}}
                    @foreach ($timeSlots as $slotIndex => $time)

                        @foreach ($weekDays as $dayIndex => $day)

                            <div class="schedule-grid-cell
                                                                                                            {{ $day->isToday() ? 'today' : '' }}"
                                style="
                                                                                                                grid-column:
                                                                                                                {{ $dayIndex + 2 }};
                                                                                                                grid-row:
                                                                                                                {{ $slotIndex + 2 }};
                                                                                                            "></div>

                        @endforeach

                    @endforeach


                    {{-- ==================================================
                    SCHEDULE CARDS
                    =================================================== --}}
                    @foreach ($boardSchedules as $schedule)

                            @php
                                $statusClass = $schedule->display_status;

                                $statusLabel = match ($schedule->display_status) {
                                    'booked' => 'Terbooking',
                                    'blocked' => 'Diblokir',
                                    'past' => 'Sudah Lewat',
                                    'invalid' => 'Tidak Valid',
                                    default => ucfirst($schedule->display_status),
                                };

                                $isCompact = (int) $schedule->board_span === 1;
                            @endphp

                            <article class="
                            schedule-event
                            schedule-event-{{ $statusClass }}
                            {{ $isCompact ? 'schedule-event-compact' : '' }}
                        " style="
                            grid-column: {{ $schedule->board_day + 2 }};
                            grid-row:
                                {{ $schedule->board_slot + 2 }}
                                /
                                span {{ $schedule->board_span }};
                        ">

                                <div class="schedule-event-top">

                                    <span class="schedule-event-status">
                                        {{ $statusLabel }}
                                    </span>

                                    <span class="schedule-event-time">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>

                                </div>

                                <strong class="schedule-event-service">
                                    {{ $schedule->service?->name ?? '-' }}
                                </strong>

                                @unless ($isCompact)

                                    @if ($schedule->photographer)
                                        <span class="schedule-event-meta">
                                            <i class="bi bi-person"></i>
                                            {{ $schedule->photographer->name }}
                                        </span>
                                    @endif

                                    @if ($schedule->studioRoom)
                                        <span class="schedule-event-meta">
                                            <i class="bi bi-door-open"></i>
                                            {{ $schedule->studioRoom->name }}
                                        </span>
                                    @endif

                                @endunless

                            </article>

                    @endforeach

                </div>

            </div>


            @if ($boardSchedules->isEmpty())

                <div class="schedule-board-empty">

                    <i class="bi bi-calendar2-week"></i>

                    <h3>
                        Belum ada jadwal minggu ini
                    </h3>

                    <p>
                        Tidak ada jadwal yang sesuai
                        dengan filter cabang dan layanan.
                    </p>

                </div>

            @endif

        @endif

    </div>


    {{-- ======================================================
    INVALID SCHEDULES
    ====================================================== --}}
    @if ($invalidSchedules->isNotEmpty())

        <div class="schedule-invalid-card">

            <div class="schedule-invalid-heading">

                <span>
                    <i class="bi bi-exclamation-triangle"></i>
                </span>

                <div>

                    <h2>
                        Jadwal Di Luar Jam Operasional
                    </h2>

                    <p>
                        Jadwal berikut tidak sesuai dengan jam buka
                        {{ $selectedBranch->name }}
                        ({{ $openingTime->format('H:i') }}
                        –
                        {{ $closingTime->format('H:i') }} WIB).
                    </p>

                </div>

            </div>


            <div class="schedule-invalid-list">

                @foreach ($invalidSchedules as $schedule)

                    <div class="schedule-invalid-item">

                        <div>

                            <strong>
                                {{ $schedule->service?->name ?? '-' }}
                            </strong>

                            <span>
                                {{ $schedule->schedule_date
                        ->locale('id')
                        ->translatedFormat(
                            'd F Y'
                        ) }}
                            </span>

                        </div>


                        <div>

                            <span>
                                {{ \Carbon\Carbon::parse(
                        $schedule->start_time
                    )->format('H:i') }}

                                –

                                {{ \Carbon\Carbon::parse(
                        $schedule->end_time
                    )->format('H:i') }}

                                WIB
                            </span>

                            <small>
                                Di luar jam operasional
                            </small>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- FOOTNOTE --}}
    <div class="schedule-board-note">

        <i class="bi bi-info-circle"></i>

        <p>
            Jadwal yang sudah lewat tetap terlihat pada minggu
            terpilih sebagai riwayat, tetapi tidak lagi dihitung
            sebagai slot tersedia untuk customer.
        </p>

    </div>

@endsection