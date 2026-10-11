@php
    $currentStep = $currentStep ?? 1;

    $steps = [
        1 => [
            'label' => 'Pilihan',
            'icon' => 'bi-grid',
        ],
        2 => [
            'label' => 'Fotografer',
            'icon' => 'bi-person',
        ],
        3 => [
            'label' => 'Jadwal',
            'icon' => 'bi-calendar3',
        ],
        4 => [
            'label' => 'Detail',
            'icon' => 'bi-file-earmark-text',
        ],
        5 => [
            'label' => 'Konfirmasi',
            'icon' => 'bi-check2-circle',
        ],
        6 => [
            'label' => 'Pembayaran',
            'icon' => 'bi-credit-card',
        ],
    ];
@endphp

<section class="booking-progress-wrap">
    <div class="booking-progress">

        @foreach ($steps as $number => $step)

            <div
                class="booking-progress-step
                {{ $number === $currentStep ? 'active' : '' }}
                {{ $number < $currentStep ? 'completed' : '' }}"
            >

                <div class="booking-progress-circle">

                    @if ($number < $currentStep)
                        <i class="bi bi-check-lg"></i>
                    @else
                        {{ $number }}
                    @endif

                </div>

                <div class="booking-progress-copy">
                    <small>Langkah {{ $number }}</small>
                    <strong>{{ $step['label'] }}</strong>
                </div>

            </div>

            @if (!$loop->last)
                <div
                    class="booking-progress-line
                    {{ $number < $currentStep ? 'completed' : '' }}">
                </div>
            @endif

        @endforeach

    </div>
</section>