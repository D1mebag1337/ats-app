<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StelleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    $stellen = \App\Models\Stelle::online()->get();

    return Inertia::render('Welcome', ['stellen' => $stellen]);
});

Route::get('/jobs', [StelleController::class, 'index'])->name('jobs.index');

Route::get('/auth', [AuthController::class, 'show'])->name('auth.show')->middleware('guest');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login')->middleware('guest');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register')->middleware('guest');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/images/{image}', [StelleController::class, 'serveImage'])->name('images.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';
