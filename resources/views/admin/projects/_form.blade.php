<div class="form-group mb-3">
    <label for="name_en">Name (EN)</label>
    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $project->getAttributes()['name_en'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="name_ru">Name (RU)</label>
    <input type="text" class="form-control" name="name_ru" value="{{ old('name_ru', $project->getAttributes()['name_ru'] ?? '') }}" />
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
    <label for="description_en">Description (EN)</label>
    <textarea class="form-control" name="description_en" rows="6">{{ old('description_en', $project->getAttributes()['description_en'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="description_ru">Description (RU)</label>
    <textarea class="form-control" name="description_ru" rows="6">{{ old('description_ru', $project->getAttributes()['description_ru'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="skill">Skills (comma or newline separated)</label>
    <textarea class="form-control" name="skill" rows="3">{{ old('skill', $project->getAttributes()['skill'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="links">Links (JSON array)</label>
    <textarea class="form-control" name="links" rows="4">{{ old('links', $project->getAttributes()['links'] ?? '') }}</textarea>
</div>
