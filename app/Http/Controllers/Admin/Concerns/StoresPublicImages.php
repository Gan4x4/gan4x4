<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait StoresPublicImages
{
    protected function storePublicImage(
        UploadedFile $file,
        string $designSubDir,
        string $fallbackBaseName = 'image',
        ?string $nameHint = null
    ): string
    {
        $targetDir = $this->designStorageDirectory($designSubDir);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $baseName = trim((string) $nameHint);
        if ($baseName === '') {
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        }

        $slug = Str::slug($baseName);
        if ($slug === '') {
            $slug = $fallbackBaseName;
        }

        $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'jpg');
        $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?: 'jpg';
        $filename = $slug . '-' . now()->format('Ymd-His') . '.' . $extension;
        $counter = 1;
        while (is_file($targetDir . DIRECTORY_SEPARATOR . $filename)) {
            $counter++;
            $filename = $slug . '-' . now()->format('Ymd-His') . '-' . $counter . '.' . $extension;
        }

        $file->move($targetDir, $filename);

        return $filename;
    }

    protected function removePublicImageIfExists(?string $filename, string $designSubDir): void
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return;
        }

        $paths = [
            $this->designStorageDirectory($designSubDir) . DIRECTORY_SEPARATOR . $filename,
            public_path('design/' . trim($designSubDir, '/') . '/' . $filename),
        ];

        foreach ($paths as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    private function designStorageDirectory(string $designSubDir): string
    {
        return storage_path('app/public/design/' . trim($designSubDir, '/'));
    }
}
