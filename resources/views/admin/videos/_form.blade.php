<div class="form-group mb-3">
    <label for="name_en">Name (EN)</label>
    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $video->getAttributes()['name_en'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="name_ru">Name (RU)</label>
    <input type="text" class="form-control" name="name_ru" value="{{ old('name_ru', $video->getAttributes()['name_ru'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="experience_id">Workplace (Experience)</label>
    <select class="form-select" name="experience_id">
        <option value="">Not linked</option>
        @foreach(($experiences ?? collect()) as $experience)
            <option value="{{ $experience->id }}" {{ (string)old('experience_id', $video->experience_id ?? '') === (string)$experience->id ? 'selected' : '' }}>
                {{ $experience->start }} @if ($experience->start != $experience->end) - {{ $experience->end ?? 'н.в.' }} @endif · {{ $experience->position }} · {{ $experience->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="url">URL</label>
    <input type="text" class="form-control" name="url" value="{{ old('url', $video->url ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="image">Image filename</label>
    <input type="text" class="form-control" name="image" value="{{ old('image', $video->image ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="image_file">Upload preview image</label>
    <input type="file" class="form-control" name="image_file" accept=".jpg,.jpeg,.png,.gif,.webp,.bmp" />
    <small class="form-text text-muted">If uploaded, this image will replace "Image filename".</small>
    @if (!empty($video->image))
        <div class="mt-2">
            <small class="text-muted">Current file: {{ $video->image }}</small>
        </div>
    @endif
</div>

<div class="form-group mb-3">
    <label for="code">Embed code</label>
    <input type="text" class="form-control" name="code" value="{{ old('code', $video->code ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="description_en">Description (EN)</label>
    <textarea class="form-control" name="description_en" rows="6">{{ old('description_en', $video->getAttributes()['description_en'] ?? '') }}</textarea>
    <small class="form-text text-muted">Use Markdown. Raw HTML is not rendered.</small>
</div>

<div class="form-group mb-3">
    <label for="description_ru">Description (RU)</label>
    <textarea class="form-control" name="description_ru" rows="6">{{ old('description_ru', $video->getAttributes()['description_ru'] ?? '') }}</textarea>
    <small class="form-text text-muted">Use Markdown. Raw HTML is not rendered.</small>
</div>
