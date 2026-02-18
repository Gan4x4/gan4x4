<div class="form-group mb-3">
    <label for="name_en">Name (EN)</label>
    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $project->getAttributes()['name_en'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="name_ru">Name (RU)</label>
    <input type="text" class="form-control" name="name_ru" value="{{ old('name_ru', $project->getAttributes()['name_ru'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="experience_id">Workplace (Experience)</label>
    <select class="form-select" name="experience_id">
        <option value="">Not linked</option>
        @foreach(($experiences ?? collect()) as $experience)
            <option value="{{ $experience->id }}" {{ (string)old('experience_id', $project->experience_id ?? '') === (string)$experience->id ? 'selected' : '' }}>
                {{ $experience->start }} @if ($experience->start != $experience->end) - {{ $experience->end ?? 'н.в.' }} @endif · {{ $experience->position }} · {{ $experience->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="start">Start</label>
    <input type="date" class="form-control" name="start" value="{{ old('start', $project->getAttributes()['start'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="end">End</label>
    <input type="date" class="form-control" name="end" value="{{ old('end', $project->getAttributes()['end'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="url">URL</label>
    <input type="text" class="form-control" name="url" value="{{ old('url', $project->url ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="logo">Logo filename</label>
    <input type="text" class="form-control" name="logo" value="{{ old('logo', $project->logo ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="logo_file">Upload logo image</label>
    <input type="file" class="form-control" name="logo_file" accept=".jpg,.jpeg,.png,.gif,.webp,.bmp" />
    <small class="form-text text-muted">If uploaded, this image will replace "Logo filename".</small>
</div>

<div class="form-group mb-3">
    <label for="description_en">Description (EN)</label>
    <textarea class="form-control" name="description_en" rows="6">{{ old('description_en', $project->getAttributes()['description_en'] ?? '') }}</textarea>
    <small class="form-text text-muted">Use Markdown. Raw HTML is not rendered.</small>
</div>

<div class="form-group mb-3">
    <label for="description_ru">Description (RU)</label>
    <textarea class="form-control" name="description_ru" rows="6">{{ old('description_ru', $project->getAttributes()['description_ru'] ?? '') }}</textarea>
    <small class="form-text text-muted">Use Markdown. Raw HTML is not rendered.</small>
</div>

<div class="form-group mb-3">
    <label for="skill">Skills (comma or newline separated)</label>
    <textarea class="form-control" name="skill" rows="3">{{ old('skill', $project->getAttributes()['skill'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="links">Links (JSON array)</label>
    @php
        $linksForForm = old('links');
        if ($linksForForm === null) {
            $rawLinks = $project->getAttributes()['links'] ?? '';
            if ($rawLinks === '') {
                $linksForForm = '';
            } else {
                $decodedLinks = json_decode($rawLinks, true);
                if (json_last_error() === JSON_ERROR_NONE && is_string($decodedLinks)) {
                    $decodedLinks = json_decode($decodedLinks, true);
                }
                if (is_array($decodedLinks)) {
                    $linksForForm = json_encode($decodedLinks, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                } else {
                    $linksForForm = $rawLinks;
                }
            }
        }
    @endphp
    <textarea class="form-control" name="links" rows="4">{{ $linksForForm ?? old('links', '') }}</textarea>
</div>
