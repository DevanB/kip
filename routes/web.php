<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrTestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dr-tests/create', [DrTestController::class, 'create'])->name('dr-tests.create');
    Route::post('dr-tests', [DrTestController::class, 'store'])->name('dr-tests.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
