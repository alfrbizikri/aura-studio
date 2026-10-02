<div class="admin-form-grid">

    <div class="admin-form-group full">

        <label for="service_id">
            Layanan <span>*</span>
        </label>

        <select
            id="service_id"
            name="service_id"
            required
        >

            <option value="">
                -- Pilih Layanan --
            </option>

            @foreach ($services as $service)

                <option
                    value="{{ $service->id }}"
                    @selected(
                        old(
                            'service_id',
                            $gallery->service_id ?? ''
                        ) == $service->id
                    )
                >
                    {{ $service->name }}
                </option>

            @endforeach

        </select>

        @error('service_id')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror

    </div>


    <div class="admin-form-group full">

        <label for="title">
            Judul Galeri <span>*</span>
        </label>

        <input
            type="text"
            id="title"
            name="title"
            value="{{ old(
                'title',
                $gallery->title ?? ''
            ) }}"
            placeholder="Contoh: Family Portrait Session"
            required
        >

        @error('title')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror

    </div>


    <div class="admin-form-group full">

        <label for="description">
            Deskripsi
        </label>

        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Deskripsi singkat foto galeri"
        >{{ old(
            'description',
            $gallery->description ?? ''
        ) }}</textarea>

        @error('description')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror

    </div>


    <div class="admin-form-group full">

        <label for="image">

            Foto

            @if (!isset($gallery))
                <span>*</span>
            @endif

        </label>


        @if (
            isset($gallery) &&
            $gallery->image
        )

            <div class="admin-current-image">

                <img
                    src="{{ asset(
                        'storage/' . $gallery->image
                    ) }}"
                    alt="{{ $gallery->title }}"
                >

                <span>
                    Foto saat ini
                </span>

            </div>

        @endif


        <input
            type="file"
            id="image"
            name="image"
            accept=".jpg,.jpeg,.png,.webp"
            {{ isset($gallery) ? '' : 'required' }}
        >

        <small class="admin-help-text">
            Format JPG, JPEG, PNG, atau WEBP.
            Maksimal 5 MB.
        </small>

        @error('image')
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
                value="published"
                @selected(
                    old(
                        'status',
                        $gallery->status ?? 'published'
                    ) === 'published'
                )
            >
                Published
            </option>

            <option
                value="hidden"
                @selected(
                    old(
                        'status',
                        $gallery->status ?? 'published'
                    ) === 'hidden'
                )
            >
                Hidden
            </option>

        </select>

        @error('status')
            <small class="admin-field-error">
                {{ $message }}
            </small>
        @enderror

    </div>

</div>