#!/usr/bin/env php
<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$inputPath = $argv[1] ?? null;
if (!$inputPath) {
    fwrite(STDERR, "Usage: php scripts/db/import_content.php /path/to/content-export.json\n");
    exit(1);
}

if (!is_file($inputPath)) {
    fwrite(STDERR, "File not found: {$inputPath}\n");
    exit(1);
}

$json = file_get_contents($inputPath);
$payload = json_decode($json, true);
if (!is_array($payload) || !isset($payload['tables']) || !is_array($payload['tables'])) {
    fwrite(STDERR, "Invalid export payload format\n");
    exit(1);
}

$tables = $payload['tables'];
$experiences = $tables['experiences'] ?? [];
$projects = $tables['projects'] ?? [];
$videos = $tables['videos'] ?? [];

DB::beginTransaction();
try {
    DB::statement('PRAGMA foreign_keys = OFF');

    DB::table('projects')->delete();
    DB::table('videos')->delete();
    DB::table('experiences')->delete();

    DB::statement("DELETE FROM sqlite_sequence WHERE name IN ('experiences','projects','videos')");

    if (!empty($experiences)) {
        DB::table('experiences')->insert($experiences);
    }
    if (!empty($projects)) {
        DB::table('projects')->insert($projects);
    }
    if (!empty($videos)) {
        DB::table('videos')->insert($videos);
    }

    DB::statement('PRAGMA foreign_keys = ON');
    DB::commit();

    echo 'Imported experiences: ' . count($experiences) . "\n";
    echo 'Imported projects: ' . count($projects) . "\n";
    echo 'Imported videos: ' . count($videos) . "\n";
    echo "Import completed\n";
} catch (Throwable $e) {
    DB::rollBack();
    DB::statement('PRAGMA foreign_keys = ON');
    fwrite(STDERR, "Import failed: " . $e->getMessage() . "\n");
    exit(1);
}

