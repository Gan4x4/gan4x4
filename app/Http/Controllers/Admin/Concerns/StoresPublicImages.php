<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait StoresPublicImages
{
    protected function storePublicImage(UploadedFile $file, string $publicDir, string $fallbackBaseName = 'image'): string
    {
        $targetDir = public_path(trim($publicDir, '/'));
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($baseName);
        if ($slug === '') {
            $slug = $fallbackBaseName;
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = $slug . '-' . time() . '-' . Str::random(6) . '.' . $extension;

        $file->move($targetDir, $filename);

        return $filename;
    }
}

