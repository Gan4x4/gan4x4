<?php

return [
    'site_url' => env('CV_SITE_URL', 'https://gan4x4.ru'),
    'cache_dir' => storage_path('app/public/cv'),
    'chrome_binary' => env('CV_CHROME_BINARY', '/usr/bin/chromium'),
    'chrome_no_sandbox' => env('CV_CHROME_NO_SANDBOX', true),
    'render_timeout_ms' => (int) env('CV_RENDER_TIMEOUT_MS', 20000),
    'file_name' => [
        'en' => 'Anton_Ganichev_CV_en.pdf',
        'ru' => 'Anton_Ganichev_CV_ru.pdf',
    ],
];
