<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BidangDinasController;
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

// Protected routes
Route::middleware(['auth', 'prevent.back.history'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // OPDs
    Route::get('opds/parents/get', [OpdController::class, 'getParents'])->name('opds.get-parents');
    Route::resource('opds', OpdController::class);

    // Regions
    Route::get('regions/parents/get', [RegionController::class, 'getParents'])->name('regions.get-parents');
    Route::get('api/regions/level/{level}', [RegionController::class, 'getByLevel'])->name('regions.get-by-level');
    Route::resource('regions', RegionController::class);

    // Bidang Dinas
    Route::get('bidang-dinas/get-parents', [BidangDinasController::class, 'getParents'])->name('bidang-dinas.get-parents');
    Route::resource('bidang-dinas', BidangDinasController::class)->parameters(['bidang-dinas' => 'bidangDinas'])->except(['create', 'show']);

    // Roles
    Route::resource('roles', RoleController::class);

    // Permissions
    Route::resource('permissions', PermissionController::class)->except(['create', 'show']);

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::delete('/activity-logs', [ActivityLogController::class, 'destroyAll'])->name('activity-logs.destroyAll');
});

// Calendar
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');
