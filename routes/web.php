<?php

use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ExperienceController as AdminExperienceController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/education', function () {
    $lang = Lang::locale();
    return view('education.'.$lang);
})->name('education');


/*
Route::get('/experience', function () {
    return view('experience');
})->name('experience');
*/

Route::resource('experience',ExperienceController::class)->names([
    'index' => 'experience'
]);

Route::resource('projects',ProjectController::class)->only(['index'])->names([
    'index' => 'projects',
    
]);

Route::resource('video',VideoController::class)->only(['index'])->names([
    'index' => 'video',
    
]);

Route::prefix('admin')->middleware('htpasswd')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('projects', AdminProjectController::class)->names('admin.projects');
    Route::resource('experiences', AdminExperienceController::class)->names('admin.experiences');
    Route::resource('videos', AdminVideoController::class)->names('admin.videos');
});
/*
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        // Matches The "/admin/users" URL
    });
});

Route::get(
    '/user/profile',
    [ProjectController::class, 'show']
)->name('profile');
*/


Route::get('/contacts', function () {
    return view('contacts');
})->name('contacts');

Route::get('/cv/download', [CvController::class, 'download'])->name('cv.download');

Route::get('/sitemap.xml', function () {
    $urls = [
        route('home'),
        route('education'),
        route('experience'),
        route('projects'),
        route('video'),
        route('contacts'),
    ];

    $lastmod = now()->toDateString();
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . e($url) . '</loc>';
        $xml .= '<lastmod>' . $lastmod . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

$resolveLocaleRedirectTarget = function (Request $request): string {
    $fallback = route('home');
    $referer = (string) $request->headers->get('referer', '');

    if ($referer === '') {
        return $fallback;
    }

    $parts = parse_url($referer);
    if (!is_array($parts)) {
        return $fallback;
    }

    $host = $parts['host'] ?? null;
    if ($host !== null && !hash_equals((string) $request->getHost(), (string) $host)) {
        return $fallback;
    }

    $path = $parts['path'] ?? '';
    if (in_array($path, ['/en', '/ru'], true)) {
        return $fallback;
    }

    return $referer;
};

Route::get('/en', function (Request $request) use ($resolveLocaleRedirectTarget) {
    App::setLocale('en');
    session()->put('locale', 'en');
    return redirect()->to($resolveLocaleRedirectTarget($request));
})->name('en');

Route::get('/ru', function (Request $request) use ($resolveLocaleRedirectTarget) {
    App::setLocale('ru');
    session()->put('locale', 'ru');
    return redirect()->to($resolveLocaleRedirectTarget($request));
})->name('ru');
