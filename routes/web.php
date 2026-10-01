<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LocalizationAnalyticsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Language Switch
|--------------------------------------------------------------------------
*/

Route::get(
    '/language/{locale}',
    [LanguageController::class, 'switch']
)->name('language.switch');


Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Language Settings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/language-settings',
        function () {
            return Inertia::render(
                'Localization/Settings'
            );
        }
    )->name('language.settings');

    Route::post(
        '/language-settings',
        [LanguageController::class, 'updatePreference']
    )->name('language.preference.update');


    /*
    |--------------------------------------------------------------------------
    | Localization Analytics
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/localization-analytics',
        [LocalizationAnalyticsController::class, 'index']
    )->name('localization.analytics');

    Route::get(
        '/localization-analytics/export',
        [LocalizationAnalyticsController::class, 'export']
    )->name('localization.analytics.export');
});


require __DIR__ . '/auth.php';