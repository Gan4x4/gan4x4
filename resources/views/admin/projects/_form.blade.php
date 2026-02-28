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
    <label>Links</label>
    @php
        $linksRows = [];
        $oldUrls = old('links_url');
        $oldRu = old('links_ru');
        $oldEn = old('links_en');

        if (is_array($oldUrls) || is_array($oldRu) || is_array($oldEn)) {
            $oldUrls = is_array($oldUrls) ? $oldUrls : [];
            $oldRu = is_array($oldRu) ? $oldRu : [];
            $oldEn = is_array($oldEn) ? $oldEn : [];
            $count = max(count($oldUrls), count($oldRu), count($oldEn));
            for ($i = 0; $i < $count; $i++) {
                $linksRows[] = [
                    'url' => (string) ($oldUrls[$i] ?? ''),
                    'ru' => (string) ($oldRu[$i] ?? ''),
                    'en' => (string) ($oldEn[$i] ?? ''),
                ];
            }
        } else {
            $rawLinks = $project->getAttributes()['links'] ?? null;
            $decodedLinks = [];
            if (is_string($rawLinks) && trim($rawLinks) !== '') {
                $decoded = json_decode($rawLinks, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $decodedLinks = $decoded;
                }
            } elseif (is_array($rawLinks)) {
                $decodedLinks = $rawLinks;
            }

            foreach ($decodedLinks as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $linksRows[] = [
                    'url' => (string) ($row[0] ?? ''),
                    'ru' => (string) ($row[1] ?? ''),
                    'en' => (string) ($row[2] ?? ''),
                ];
            }
        }

        if ($linksRows === []) {
            $linksRows[] = ['url' => '', 'ru' => '', 'en' => ''];
        }
    @endphp

    <div id="project-links-editor" class="d-grid gap-2">
        @foreach($linksRows as $row)
            <div class="row g-2 align-items-end" data-link-row>
                <div class="col-md-5">
                    <label class="form-label">URL</label>
                    <input type="text" class="form-control" name="links_url[]" value="{{ $row['url'] }}" placeholder="https://example.com">
                </div>
                <div class="col-md-3">
                    <label class="form-label">RU name</label>
                    <input type="text" class="form-control" name="links_ru[]" value="{{ $row['ru'] }}" placeholder="Название (RU)">
                </div>
                <div class="col-md-3">
                    <label class="form-label">EN name</label>
                    <input type="text" class="form-control" name="links_en[]" value="{{ $row['en'] }}" placeholder="Name (EN)">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="button" class="btn btn-outline-success btn-sm" data-link-add title="Add row">+</button>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-link-remove title="Remove row">-</button>
                </div>
            </div>
        @endforeach
    </div>
    @error('links_url')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Use + / - to manage rows. Data will be saved as JSON.</small>
</div>

<template id="project-link-row-template">
    <div class="row g-2 align-items-end" data-link-row>
        <div class="col-md-5">
            <label class="form-label">URL</label>
            <input type="text" class="form-control" name="links_url[]" placeholder="https://example.com">
        </div>
        <div class="col-md-3">
            <label class="form-label">RU name</label>
            <input type="text" class="form-control" name="links_ru[]" placeholder="Название (RU)">
        </div>
        <div class="col-md-3">
            <label class="form-label">EN name</label>
            <input type="text" class="form-control" name="links_en[]" placeholder="Name (EN)">
        </div>
        <div class="col-md-1 d-flex gap-1">
            <button type="button" class="btn btn-outline-success btn-sm" data-link-add title="Add row">+</button>
            <button type="button" class="btn btn-outline-danger btn-sm" data-link-remove title="Remove row">-</button>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = document.getElementById('project-links-editor');
    const template = document.getElementById('project-link-row-template');
    if (!editor || !template || editor.dataset.bound === '1') {
        return;
    }
    editor.dataset.bound = '1';

    editor.addEventListener('click', function (event) {
        const addButton = event.target.closest('[data-link-add]');
        if (addButton) {
            event.preventDefault();
            const row = template.content.firstElementChild.cloneNode(true);
            editor.appendChild(row);
            return;
        }

        const removeButton = event.target.closest('[data-link-remove]');
        if (!removeButton) {
            return;
        }
        event.preventDefault();
        const row = removeButton.closest('[data-link-row]');
        if (!row) {
            return;
        }

        const rows = editor.querySelectorAll('[data-link-row]');
        if (rows.length <= 1) {
            row.querySelectorAll('input').forEach((input) => {
                input.value = '';
            });
            return;
        }
        row.remove();
    });
});
</script>
