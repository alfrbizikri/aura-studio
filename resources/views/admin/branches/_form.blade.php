<div class="admin-form-grid">

    <div class="admin-form-group full">
        <label for="name">
            Nama Cabang <span>*</span>
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $branch->name ?? '') }}"
            placeholder="Contoh: Aura Studio Banda Aceh"
            required
        >

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

        <textarea
            id="address"
            name="address"
            rows="4"
            placeholder="Masukkan alamat lengkap cabang"
            required
        >{{ old('address', $branch->address ?? '') }}</textarea>

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

        <input
            type="text"
            id="phone"
            name="phone"
            value="{{ old('phone', $branch->phone ?? '') }}"
            placeholder="08xxxxxxxxxx"
        >

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

        <input
            type="url"
            id="maps_url"
            name="maps_url"
            value="{{ old('maps_url', $branch->maps_url ?? '') }}"
            placeholder="https://maps.google.com/..."
        >

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

        <input
            type="time"
            id="opening_time"
            name="opening_time"
            value="{{ old(
                'opening_time',
                isset($branch)
                    ? \Carbon\Carbon::parse($branch->opening_time)->format('H:i')
                    : ''
            ) }}"
            required
        >

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

        <input
            type="time"
            id="closing_time"
            name="closing_time"
            value="{{ old(
                'closing_time',
                isset($branch)
                    ? \Carbon\Carbon::parse($branch->closing_time)->format('H:i')
                    : ''
            ) }}"
            required
        >

        @error('closing_time')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    <div class="admin-form-group full">
        <label for="status">
            Status <span>*</span>
        </label>

        <select
            id="status"
            name="status"
            required
        >

            <option
                value="active"
                @selected(
                    old('status', $branch->status ?? 'active') === 'active'
                )
            >
                Aktif
            </option>

            <option
                value="inactive"
                @selected(
                    old('status', $branch->status ?? 'active') === 'inactive'
                )
            >
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