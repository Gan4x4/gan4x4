<?php

namespace App\Services\Cv;

use App\EnhancedModel;
use App\Experience;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use HeadlessChromium\BrowserFactory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use RuntimeException;

class CvPdfGenerator
{
    public function downloadPath(string $locale): string
    {
        $locale = $this->normalizeLocale($locale);
        $this->ensureCacheDir();

        $pdfPath = $this->pdfPath($locale);
        $metaPath = $this->metaPath($locale);
        $fingerprint = $this->fingerprint($locale);
        $metadata = $this->readMetadata($metaPath);

        if (is_file($pdfPath) && ($metadata['fingerprint'] ?? null) === $fingerprint) {
            return $pdfPath;
        }

        $this->renderPdf($locale, $pdfPath);
        file_put_contents($metaPath, json_encode([
            'fingerprint' => $fingerprint,
            'generated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $pdfPath;
    }

    public function fileName(string $locale): string
    {
        $locale = $this->normalizeLocale($locale);
        return config("cv.file_name.$locale", "Anton_Ganichev_CV_$locale.pdf");
    }

    private function renderPdf(string $locale, string $pdfPath): void
    {
        $previousLocale = App::getLocale();
        App::setLocale($locale);

        try {
            $html = View::make('cv.pdf', [
                'cv' => $this->content($locale),
                'locale' => $locale,
                'generatedAt' => now(),
            ])->render();
        } finally {
            App::setLocale($previousLocale);
        }

        $binary = config('cv.chrome_binary') ?: null;
        $browserFactory = new BrowserFactory($binary);
        $browser = $browserFactory->createBrowser([
            'headless' => true,
            'noSandbox' => (bool) config('cv.chrome_no_sandbox', true),
            'startupTimeout' => (int) ceil(config('cv.render_timeout_ms', 20000) / 1000),
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
                '--font-render-hinting=none',
                '--no-crash-upload',
            ],
        ]);

        try {
            $page = $browser->createPage();
            $timeout = (int) config('cv.render_timeout_ms', 20000);
            $page->setHtml($html, $timeout);
            $page->pdf([
                'printBackground' => true,
                'preferCSSPageSize' => true,
                'marginTop' => 0,
                'marginBottom' => 0,
                'marginLeft' => 0,
                'marginRight' => 0,
            ])->saveToFile($pdfPath, $timeout);
        } finally {
            $browser->close();
        }

        if (!is_file($pdfPath) || filesize($pdfPath) < 1000) {
            throw new RuntimeException('CV PDF generation failed or produced an empty file.');
        }
    }

    private function content(string $locale): array
    {
        $home = $this->homeContent($locale);

        return [
            'name' => $home['name'],
            'headline' => $home['headline'],
            'summary' => $home['summary'],
            'sections' => $home['sections'],
            'contacts' => $this->contacts($locale),
            'experiences' => $this->experiences($locale),
        ];
    }

    private function homeContent(string $locale): array
    {
        $html = View::make("home.$locale")->render();
        $document = $this->document($html);
        $xpath = new DOMXPath($document);

        $name = trim($xpath->evaluate('string(//h1[contains(concat(" ", normalize-space(@class), " "), " name ")])'));
        $headline = trim($xpath->evaluate('string(//h2[contains(concat(" ", normalize-space(@class), " "), " subtitle ")])'));

        $contentNode = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " md-links-soft ")]')->item(0);
        if (!$contentNode instanceof DOMElement) {
            return [
                'name' => $name,
                'headline' => $headline,
                'summary' => '',
                'sections' => [],
            ];
        }

        $summary = '';
        $sections = [];
        $currentSection = null;
        $currentHtml = [];

        foreach ($contentNode->childNodes as $node) {
            if ($this->isEmptyText($node)) {
                continue;
            }

            if ($node instanceof DOMElement && strtolower($node->tagName) === 'p' && $summary === '') {
                $summary = $this->cleanHtml($document->saveHTML($node));
                continue;
            }

            if ($node instanceof DOMElement && strtolower($node->tagName) === 'h5') {
                if ($currentSection !== null) {
                    $sections[] = ['title' => $currentSection, 'html' => $this->cleanHtml(implode('', $currentHtml))];
                }
                $currentSection = trim($node->textContent);
                $currentHtml = [];
                continue;
            }

            if ($currentSection !== null) {
                $currentHtml[] = $document->saveHTML($node);
            }
        }

        if ($currentSection !== null) {
            $sections[] = ['title' => $currentSection, 'html' => $this->cleanHtml(implode('', $currentHtml))];
        }

        return [
            'name' => $name,
            'headline' => $headline,
            'summary' => $summary,
            'sections' => array_values(array_filter($sections, fn (array $section) => trim(strip_tags($section['html'])) !== '')),
        ];
    }

    private function contacts(string $locale): array
    {
        $siteUrl = $this->siteUrl();
        $siteLabel = parse_url($siteUrl, PHP_URL_HOST) ?: $siteUrl;

        return [
            ['label' => $locale === 'ru' ? 'Сайт' : 'Web', 'html' => '<a href="' . e($siteUrl) . '">' . e($siteLabel) . '</a>'],
            ['label' => 'Email', 'html' => '<a href="mailto:gan4x4@gmail.com">gan4x4@gmail.com</a>'],
            ['label' => 'LinkedIn', 'html' => '<a href="https://www.linkedin.com/in/anton-ganichev-46528640/">anton-ganichev</a>'],
            ['label' => 'GitHub', 'html' => '<a href="https://github.com/Gan4x4/">github.com/Gan4x4</a>'],
        ];
    }

    private function experiences(string $locale): array
    {
        return Experience::query()
            ->orderByDesc('start')
            ->get()
            ->map(function (Experience $experience) use ($locale): array {
                $attributes = $experience->getAttributes();

                return [
                    'period' => $this->period($attributes['start'] ?? null, $attributes['end'] ?? null, $locale),
                    'company' => (string) ($attributes["name_$locale"] ?? ''),
                    'url' => (string) ($attributes['url'] ?? ''),
                    'position' => (string) ($attributes["position_$locale"] ?? ''),
                    'description' => $this->cleanHtml(EnhancedModel::text2web($attributes["description_$locale"] ?? '')),
                    'duties' => $this->cleanHtml(EnhancedModel::text2web($attributes["duties_$locale"] ?? '')),
                ];
            })
            ->all();
    }

    private function period(?string $start, ?string $end, string $locale): string
    {
        $startYear = $start ? substr($start, 0, 4) : '';
        $endYear = $end ? substr($end, 0, 4) : ($locale === 'ru' ? 'н.в.' : 'present');

        return trim($startYear . ' - ' . $endYear, ' -');
    }

    private function fingerprint(string $locale): string
    {
        $homeFile = resource_path("views/home/$locale.blade.php");
        $templateFile = resource_path('views/cv/pdf.blade.php');
        $lastExperienceUpdate = (string) Experience::query()->max('updated_at');

        return hash('sha256', json_encode([
            'locale' => $locale,
            'site_url' => $this->siteUrl(),
            'home' => is_file($homeFile) ? [filemtime($homeFile), hash_file('sha256', $homeFile)] : null,
            'template' => is_file($templateFile) ? [filemtime($templateFile), hash_file('sha256', $templateFile)] : null,
            'experience_updated_at' => $lastExperienceUpdate,
            'experience_count' => Experience::query()->count(),
        ], JSON_UNESCAPED_SLASHES));
    }

    private function cleanHtml(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $document = $this->document($html);
        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//a') as $anchor) {
            if (!$anchor instanceof DOMElement) {
                continue;
            }

            $href = trim($anchor->getAttribute('href'));
            if ($href === '' || str_contains($href, 'CV_Anton_Ganichev.pdf') || str_contains($href, '/cv/download')) {
                $anchor->parentNode?->removeChild($anchor);
                continue;
            }

            $anchor->setAttribute('href', $this->absoluteUrl($href));
        }

        $body = $document->getElementsByTagName('body')->item(0);
        if (!$body) {
            return '';
        }

        $out = '';
        foreach ($body->childNodes as $node) {
            $out .= $document->saveHTML($node);
        }

        return trim($out);
    }

    private function absoluteUrl(string $href): string
    {
        if (preg_match('~^(https?:|mailto:|tel:)~i', $href) === 1) {
            return $href;
        }

        if (str_starts_with($href, '#')) {
            return $this->siteUrl() . $href;
        }

        return rtrim($this->siteUrl(), '/') . '/' . ltrim($href, '/');
    }

    private function siteUrl(): string
    {
        return rtrim((string) config('cv.site_url', 'https://gan4x4.ru'), '/');
    }

    private function document(string $html): DOMDocument
    {
        $document = new DOMDocument('1.0', 'UTF-8');

        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?><!doctype html><html><body>' . $html . '</body></html>');
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $document;
    }

    private function isEmptyText(DOMNode $node): bool
    {
        return $node->nodeType === XML_TEXT_NODE && trim($node->textContent) === '';
    }

    private function ensureCacheDir(): void
    {
        File::ensureDirectoryExists(config('cv.cache_dir'), 0775, true);
    }

    private function pdfPath(string $locale): string
    {
        return rtrim(config('cv.cache_dir'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->fileName($locale);
    }

    private function metaPath(string $locale): string
    {
        return rtrim(config('cv.cache_dir'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Anton_Ganichev_CV_$locale.json";
    }

    private function readMetadata(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }

        $data = json_decode((string) file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }

    private function normalizeLocale(string $locale): string
    {
        return in_array($locale, ['ru', 'en'], true) ? $locale : 'en';
    }
}
