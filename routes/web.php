<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StelleController;

Route::get('/', function () {
    $stellen = \App\Models\Stelle::online()->get();

    return Inertia::render('Welcome', ['stellen' => $stellen]);
});

Route::get('/jobs', [StelleController::class, 'index'])->name('jobs.index');
Route::get('/images/{image}', [StelleController::class, 'serveImage'])->name('images.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
