<?php

namespace App\Http\Controllers;

use App\Services\Cv\CvPdfGenerator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CvController extends Controller
{
    public function download(CvPdfGenerator $generator): BinaryFileResponse
    {
        $locale = app()->getLocale();
        $path = $generator->downloadPath($locale);

        return response()->download($path, $generator->fileName($locale), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
