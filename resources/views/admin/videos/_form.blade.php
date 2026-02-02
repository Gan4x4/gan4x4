<div class="form-group mb-3">
    <label for="name_en">Name (EN)</label>
    <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $video->getAttributes()['name_en'] ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="name_ru">Name (RU)</label>
    <input type="text" class="form-control" name="name_ru" value="{{ old('name_ru', $video->getAttributes()['name_ru'] ?? '') }}" />
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
    <label for="code">Embed code</label>
    <input type="text" class="form-control" name="code" value="{{ old('code', $video->code ?? '') }}" />
</div>

<div class="form-group mb-3">
    <label for="description_en">Description (EN)</label>
    <textarea class="form-control" name="description_en" rows="6">{{ old('description_en', $video->getAttributes()['description_en'] ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="description_ru">Description (RU)</label>
    <textarea class="form-control" name="description_ru" rows="6">{{ old('description_ru', $video->getAttributes()['description_ru'] ?? '') }}</textarea>
</div>
