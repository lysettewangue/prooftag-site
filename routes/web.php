<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/fr/{path?}', function (?string $path = null) {
    $suffix = ltrim((string) $path, '/');

    return redirect($suffix === '' ? '/' : '/'.$suffix, 301);
})->where('path', '.*');

Route::middleware('locale:fr')->name('fr.')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
});

Route::prefix('en')->middleware('locale:en')->name('en.')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
});
