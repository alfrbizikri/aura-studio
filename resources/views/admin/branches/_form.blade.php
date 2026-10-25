@push('styles')
    <style>
        .branch-services-help {
            margin: -2px 0 14px;
            color: var(--a-soft);
            font-size: 0.83rem;
            line-height: 1.7;
        }

        .branch-services-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .admin-form-group .branch-service-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 0;
            padding: 15px;
            border: 1px solid var(--a-line-strong);
            border-radius: var(--a-r-md);
            background: #fff;
            cursor: pointer;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .admin-form-group .branch-service-option:hover {
            border-color: var(--a-gold);
        }

        .admin-form-group .branch-service-option input[type="checkbox"] {
            width: 18px;
            min-width: 18px;
            height: 18px;
            margin: 3px 0 0;
            padding: 0;
            accent-color: var(--a-gold);
            cursor: pointer;
        }

        .branch-service-info {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 5px;
        }

        .branch-service-info strong {
            color: var(--a-ink);
            font-size: 0.9rem;
        }

        .branch-service-info small {
            color: var(--a-ok);
            font-size: 0.75rem;
            line-height: 1.5;
            font-weight: 500;
        }

        .branch-service-info small.is-inactive {
            color: var(--a-bad);
        }

        .branch-services-empty {
            padding: 18px;
            border: 1px dashed var(--a-line-strong);
            border-radius: var(--a-r-md);
            color: var(--a-soft);
            font-size: 0.88rem;
        }

        @media (max-width: 640px) {
            .branch-services-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }
    </style>
@endpush

@php
    $selectedServiceIds = old(
        'services',
        isset($branch)
        ? $branch->services->pluck('id')->all()
        : []
    );

    $selectedServiceIds = array_map(
        'intval',
        (array) $selectedServiceIds
    );
@endphp

<div class="admin-form-grid">

    <div class="admin-form-group full">
        <label for="name">
            Nama Cabang <span>*</span>
        </label>

        <input type="text" id="name" name="name" value="{{ old('name', $branch->name ?? '') }}"
            placeholder="Contoh: Aura Studio Banda Aceh" required>

        @error('name')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    <div class="admin-form-group full">
        <label for="address">
            Alamat <span>*</span>
        </label>

        <textarea id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap cabang"
            required>{{ old('address', $branch->address ?? '') }}</textarea>

        @error('address')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    <div class="admin-form-group">
        <label for="phone">
            Nomor Telepon
        </label>

        <input type="text" id="phone" name="phone" value="{{ old('phone', $branch->phone ?? '') }}"
            placeholder="08xxxxxxxxxx">

        @error('phone')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    <div class="admin-form-group">
        <label for="maps_url">
            URL Google Maps
        </label>

        <input type="url" id="maps_url" name="maps_url" value="{{ old('maps_url', $branch->maps_url ?? '') }}"
            placeholder="https://maps.google.com/...">

        @error('maps_url')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    <div class="admin-form-group">
        <label for="opening_time">
            Jam Buka <span>*</span>
        </label>

        <input type="time" id="opening_time" name="opening_time" value="{{ old(
    'opening_time',
    isset($branch)
    ? \Carbon\Carbon::parse($branch->opening_time)->format('H:i')
    : ''
) }}" required>

        @error('opening_time')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    <div class="admin-form-group">
        <label for="closing_time">
            Jam Tutup <span>*</span>
        </label>

        <input type="time" id="closing_time" name="closing_time" value="{{ old(
    'closing_time',
    isset($branch)
    ? \Carbon\Carbon::parse($branch->closing_time)->format('H:i')
    : ''
) }}" required>

        @error('closing_time')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>



    {{-- LAYANAN YANG TERSEDIA DI CABANG --}}
    <div class="admin-form-group full">

        <label>
            Layanan yang Tersedia di Cabang
        </label>

        <p class="branch-services-help">
            Centang layanan yang ditawarkan oleh cabang ini.
            Layanan yang tidak dicentang tidak akan tersedia untuk dipilih
            pada proses booking cabang tersebut.
        </p>

        <div class="branch-services-grid">

            @forelse ($services as $service)

                <label class="branch-service-option">

                    <input type="checkbox" name="services[]" value="{{ $service->id }}" @checked(
                        in_array(
                            (int) $service->id,
                            $selectedServiceIds,
                            true
                        )
                    )>

                    <span class="branch-service-info">

                        <strong>
                            {{ $service->name }}
                        </strong>

                        @if ($service->status === 'active')

                            <small>
                                <i class="bi bi-check-circle"></i>
                                Layanan aktif
                            </small>

                        @else

                            <small class="is-inactive">
                                <i class="bi bi-exclamation-circle"></i>
                                Nonaktif secara global — belum dapat dipesan
                            </small>

                        @endif

                    </span>

                </label>

            @empty

                <div class="branch-services-empty">
                    Belum ada data layanan. Silakan tambahkan layanan
                    terlebih dahulu melalui menu Kelola Layanan.
                </div>

            @endforelse

        </div>

        @error('services')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror

    </div>



    <div class="admin-form-group full">
        <label for="status">
            Status <span>*</span>
        </label>

        <select id="status" name="status" required>

            <option value="active" @selected(
                old('status', $branch->status ?? 'active') === 'active'
            )>
                Aktif
            </option>

            <option value="inactive" @selected(
                old('status', $branch->status ?? 'active') === 'inactive'
            )>
                Tidak Aktif
            </option>

        </select>

        @error('status')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>

</div>