#!/usr/bin/env php
<?php

declare(strict_types=1);

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Page;

require __DIR__ . '/../../vendor/autoload.php';

$baseUrl = rtrim(getenv('CV_VERIFY_BASE_URL') ?: 'https://127.0.0.1:8443', '/');
$downloadRoot = getenv('CV_VERIFY_DOWNLOAD_DIR') ?: sys_get_temp_dir() . '/gan4x4-cv-download-' . getmypid();
$chromeBinary = getenv('CV_VERIFY_CHROME_BINARY') ?: getenv('CV_CHROME_BINARY') ?: '/usr/bin/chromium';
$timeoutMs = (int) (getenv('CV_VERIFY_TIMEOUT_MS') ?: 30000);
$locales = array_values(array_filter(array_map('trim', explode(',', getenv('CV_VERIFY_LOCALES') ?: 'en,ru'))));

$expectedChapters = [
    'en' => [
        'Anton Ganichev',
        'What I deliver',
        'Primary focus',
        'Background (familiar topics)',
        'Work format',
        'Experience',
        'gan4x4.ru',
    ],
    'ru' => [
        'Антон Ганичев',
        'Ключевые компетенции',
        'Основные направления работы',
        'Профессиональные знания',
        'Формат работы',
        'Опыт',
        'gan4x4.ru',
    ],
];

$forbiddenPdfText = [
    '127.0.0.1',
    'localhost',
    '162.245.191.118',
];

if (!is_executable($chromeBinary)) {
    fail("Chromium binary is not executable: {$chromeBinary}");
}

if (!commandExists('pdftotext')) {
    fail('pdftotext is not available. Install poppler-utils in the runtime image.');
}

ensureDirectory($downloadRoot);
clearDirectory($downloadRoot);

$browserFactory = new BrowserFactory($chromeBinary);
$browser = $browserFactory->createBrowser([
    'headless' => true,
    'noSandbox' => true,
    'startupTimeout' => (int) ceil($timeoutMs / 1000),
    'envVariables' => [
        'HOME' => sys_get_temp_dir(),
        'XDG_CONFIG_HOME' => sys_get_temp_dir() . '/chromium-config',
        'XDG_CACHE_HOME' => sys_get_temp_dir() . '/chromium-cache',
    ],
    'customFlags' => [
        '--disable-gpu',
        '--disable-dev-shm-usage',
        '--disable-crash-reporter',
        '--disable-crashpad',
        '--ignore-certificate-errors',
        '--no-crash-upload',
        '--window-size=390,844',
    ],
]);

try {
    foreach ($locales as $locale) {
        if (!isset($expectedChapters[$locale])) {
            fail("Unsupported CV verification locale: {$locale}");
        }

        verifyLocale($browser, $baseUrl, $downloadRoot, $locale, $expectedChapters[$locale], $forbiddenPdfText, $timeoutMs);
    }
} finally {
    $browser->close();
}

echo 'CV browser download verification passed for: ' . implode(', ', $locales) . PHP_EOL;

function verifyLocale($browser, string $baseUrl, string $downloadRoot, string $locale, array $expectedChapters, array $forbiddenPdfText, int $timeoutMs): void
{
    $localeDownloadDir = $downloadRoot . DIRECTORY_SEPARATOR . $locale;
    ensureDirectory($localeDownloadDir);
    clearDirectory($localeDownloadDir);

    $page = $browser->createPage();

    try {
        $page->setDeviceMetricsOverride([
            'width' => 390,
            'height' => 844,
            'deviceScaleFactor' => 2,
            'mobile' => true,
        ])->await($timeoutMs);
        $page->setDownloadPath($localeDownloadDir);

        $navigation = $page->navigate($baseUrl . '/' . $locale);
        $navigation->waitForNavigation(Page::DOM_CONTENT_LOADED, $timeoutMs);
        $page->waitUntilContainsElement('a[href*="/cv/download"]', $timeoutMs);

        $clickResult = $page->evaluate(<<<'JS'
(() => {
    const links = Array.from(document.querySelectorAll(
        'a[download][href*="/cv/download"], a[aria-label="Download Anton Ganichev CV"], a[href*="/cv/download"]'
    ));
    const link = links.find((item) => item.offsetParent !== null) || links[0] || null;

    if (!link) {
        return { clicked: false, reason: 'CV download link not found' };
    }

    link.scrollIntoView({ block: 'center', inline: 'center' });
    link.click();

    return {
        clicked: true,
        href: link.href,
        label: link.getAttribute('aria-label') || link.textContent.trim()
    };
})()
JS)->getReturnValue($timeoutMs);

        if (!is_array($clickResult) || ($clickResult['clicked'] ?? false) !== true) {
            $reason = is_array($clickResult) ? ($clickResult['reason'] ?? 'unknown') : 'unknown';
            fail("Could not click CV download link for {$locale}: {$reason}");
        }

        $pdfPath = waitForDownloadedPdf($localeDownloadDir, $timeoutMs);
        $pdfText = pdfText($pdfPath);
        assertContainsAll($pdfText, $expectedChapters, $locale, $pdfPath);
        assertContainsNone($pdfText, $forbiddenPdfText, $locale, $pdfPath);

        echo "{$locale}: downloaded and verified " . basename($pdfPath) . PHP_EOL;
    } finally {
        $page->close();
    }
}

function assertContainsNone(string $text, array $forbidden, string $locale, string $pdfPath): void
{
    $found = [];

    foreach ($forbidden as $value) {
        if (mb_stripos($text, $value) !== false) {
            $found[] = $value;
        }
    }

    if ($found !== []) {
        fail("Downloaded {$locale} CV contains non-public site URLs in {$pdfPath}: " . implode(', ', $found));
    }
}

function waitForDownloadedPdf(string $directory, int $timeoutMs): string
{
    $deadline = microtime(true) + ($timeoutMs / 1000);

    do {
        $inProgress = glob($directory . DIRECTORY_SEPARATOR . '*.crdownload') ?: [];
        $pdfs = glob($directory . DIRECTORY_SEPARATOR . '*.pdf') ?: [];

        foreach ($pdfs as $pdf) {
            clearstatcache(true, $pdf);
            if (is_file($pdf) && filesize($pdf) > 1000 && $inProgress === []) {
                return $pdf;
            }
        }

        usleep(200000);
    } while (microtime(true) < $deadline);

    $files = array_map('basename', glob($directory . DIRECTORY_SEPARATOR . '*') ?: []);
    fail('CV PDF was not downloaded. Files in download directory: ' . ($files ? implode(', ', $files) : 'none'));
}

function pdfText(string $pdfPath): string
{
    $command = 'pdftotext -layout ' . escapeshellarg($pdfPath) . ' - 2>&1';
    $text = shell_exec($command);

    if (!is_string($text) || trim($text) === '') {
        fail("Could not extract text from downloaded PDF: {$pdfPath}");
    }

    return preg_replace('/\s+/u', ' ', $text) ?? $text;
}

function assertContainsAll(string $text, array $expected, string $locale, string $pdfPath): void
{
    $missing = [];

    foreach ($expected as $chapter) {
        if (mb_stripos($text, $chapter) === false) {
            $missing[] = $chapter;
        }
    }

    if ($missing !== []) {
        fail("Downloaded {$locale} CV is missing chapters in {$pdfPath}: " . implode(', ', $missing));
    }
}

function commandExists(string $command): bool
{
    $result = trim((string) shell_exec('command -v ' . escapeshellarg($command) . ' 2>/dev/null'));
    return $result !== '';
}

function ensureDirectory(string $directory): void
{
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        fail("Could not create directory: {$directory}");
    }
}

function clearDirectory(string $directory): void
{
    foreach (glob($directory . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
        if (is_dir($path)) {
            clearDirectory($path);
            rmdir($path);
            continue;
        }

        unlink($path);
    }
}

function fail(string $message): never
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}
