#!/usr/bin/env php
<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$timestamp = date('Ymd_His');
$backupDir = __DIR__ . '/../../storage/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0775, true);
}

$tables = ['experiences', 'projects', 'videos'];
$payload = [
    'meta' => [
        'created_at' => date(DATE_ATOM),
        'connection' => config('database.default'),
    ],
    'tables' => [],
];

foreach ($tables as $table) {
    $rows = DB::table($table)->orderBy('id')->get()->map(function ($row) {
        return (array) $row;
    })->all();

    $payload['tables'][$table] = $rows;
    echo $table . ': ' . count($rows) . " rows\n";
}

$outputPath = $backupDir . '/content-export-' . $timestamp . '.json';
file_put_contents(
    $outputPath,
    json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);

echo "Export written to: {$outputPath}\n";

