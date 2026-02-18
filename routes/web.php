<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Captcha Refresh (AJAX)
Route::get('/captcha/refresh', [LoginController::class, 'refreshCaptcha'])
    ->name('captcha.refresh');

// dashboard pages
Route::middleware(['auth', 'prevent.back.history'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Opds
    Route::resource('opds', OpdController::class);
    Route::get('opds/parents/get', [OpdController::class, 'getParents'])->name('opds.get-parents');

    // Regions
    Route::resource('regions', RegionController::class);
    Route::get('regions/parents/get', [RegionController::class, 'getParents'])->name('regions.get-parents');
    Route::get('api/regions/level/{level}', [RegionController::class, 'getByLevel'])->name('regions.get-by-level');

    // Roles
    Route::resource('roles', RoleController::class);

    //Permission
    Route::resource('permissions', PermissionController::class)->except(['create', 'show']);

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::delete('/activity-logs', [ActivityLogController::class, 'destroyAll'])->name('activity-logs.destroyAll');
});

// // calender pages
// Route::get('/calendar', function () {
//     return view('pages.calender', ['title' => 'Calendar']);
// })->name('calendar');

// // profile pages
// Route::get('/profile', function () {
//     return view('pages.profile', ['title' => 'Profile']);
// })->name('profile');

// // form pages
// Route::get('/form-elements', function () {
//     return view('pages.form.form-elements', ['title' => 'Form Elements']);
// })->name('form-elements');

// // tables pages
// Route::get('/basic-tables', function () {
//     return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
// })->name('basic-tables');

// // pages

// Route::get('/blank', function () {
//     return view('pages.blank', ['title' => 'Blank']);
// })->name('blank');

// // error pages
// Route::get('/error-404', function () {
//     return view('pages.errors.error-404', ['title' => 'Error 404']);
// })->name('error-404');

// // chart pages
// Route::get('/line-chart', function () {
//     return view('pages.chart.line-chart', ['title' => 'Line Chart']);
// })->name('line-chart');

// Route::get('/bar-chart', function () {
//     return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
// })->name('bar-chart');

// // ui elements pages
// Route::get('/alerts', function () {
//     return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
// })->name('alerts');

// Route::get('/avatars', function () {
//     return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
// })->name('avatars');

// Route::get('/badge', function () {
//     return view('pages.ui-elements.badges', ['title' => 'Badges']);
// })->name('badges');

// Route::get('/buttons', function () {
//     return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
// })->name('buttons');

// Route::get('/image', function () {
//     return view('pages.ui-elements.images', ['title' => 'Images']);
// })->name('images');

// Route::get('/videos', function () {
//     return view('pages.ui-elements.videos', ['title' => 'Videos']);
// })->name('videos');
