<?php

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

Route::resource('admin/projects',ProjectController::class)->only(['edit','update']);
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


Route::get('/en', function () {
    App::setLocale('en');
    session()->put('locale', 'en');
    return redirect()->back();
})->name('en');

Route::get('/ru', function () {
    App::setLocale('ru');
    session()->put('locale', 'ru');
    return redirect()->back();
})->name('ru');

