<div class="form-group mb-3">
    <label for="name_en">Company (EN)</label>
    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $experience->getAttributes()['name_en'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="name_ru">Company (RU)</label>
    <input type="text" class="form-control" name="name_ru" value="{{ old('name_ru', $experience->getAttributes()['name_ru'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="position_en">Position (EN)</label>
    <input type="text" class="form-control" name="position_en" value="{{ old('position_en', $experience->getAttributes()['position_en'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="position_ru">Position (RU)</label>
    <input type="text" class="form-control" name="position_ru" value="{{ old('position_ru', $experience->getAttributes()['position_ru'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="start">Start</label>
    <input type="date" class="form-control" name="start" value="{{ old('start', $experience->getAttributes()['start'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="end">End</label>
    <input type="date" class="form-control" name="end" value="{{ old('end', $experience->getAttributes()['end'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="url">URL</label>
    <input type="text" class="form-control" name="url" value="{{ old('url', $experience->url ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="logo">Logo filename</label>
    <input type="text" class="form-control" name="logo" value="{{ old('logo', $experience->logo ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="description_en">Description (EN)</label>
    <textarea class="form-control" name="description_en" rows="4">{{ old('description_en', $experience->getAttributes()['description_en'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="description_ru">Description (RU)</label>
    <textarea class="form-control" name="description_ru" rows="4">{{ old('description_ru', $experience->getAttributes()['description_ru'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="duties_en">Duties (EN)</label>
    <textarea class="form-control" name="duties_en" rows="6">{{ old('duties_en', $experience->getAttributes()['duties_en'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="duties_ru">Duties (RU)</label>
    <textarea class="form-control" name="duties_ru" rows="6">{{ old('duties_ru', $experience->getAttributes()['duties_ru'] ?? '') }}</textarea>
</div>
