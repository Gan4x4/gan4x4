<?php

namespace Tests\Feature;

use App\Services\Cv\CvPdfGenerator;
use Tests\TestCase;

class CvDownloadTest extends TestCase
{
    public function test_home_page_uses_generated_cv_download_route(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('/cv/download', false);
        $response->assertDontSee('/storage/CV_Anton_Ganichev.pdf', false);
    }

    public function test_cv_download_route_returns_pdf_response(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'cv-test-');
        file_put_contents($path, "%PDF-1.4\n% test pdf\n");

        $this->app->instance(CvPdfGenerator::class, new class($path) extends CvPdfGenerator {
            public function __construct(private string $path)
            {
            }

            public function downloadPath(string $locale): string
            {
                return $this->path;
            }

            public function fileName(string $locale): string
            {
                return 'Anton_Ganichev_CV_test.pdf';
            }
        });

        $response = $this->get('/cv/download');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertDownload('Anton_Ganichev_CV_test.pdf');
    }
}
