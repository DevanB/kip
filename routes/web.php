<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
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
    Route::get('dr-tests', [DrTestController::class, 'index'])->name('dr-tests.index');
    Route::get('dr-tests/create', [DrTestController::class, 'create'])->name('dr-tests.create');
    Route::get('dr-tests/{drTest}', [DrTestController::class, 'show'])->name('dr-tests.show');
    Route::get('dr-tests/{drTest}/edit', [DrTestController::class, 'edit'])->name('dr-tests.edit');
    Route::post('dr-tests', [DrTestController::class, 'store'])->name('dr-tests.store');
    Route::put('dr-tests/{drTest}', [DrTestController::class, 'update'])->name('dr-tests.update');
    Route::delete('dr-tests/{drTest}', [DrTestController::class, 'destroy'])->name('dr-tests.destroy');

    Route::get('developers', [DeveloperController::class, 'index'])->name('developers.index');
    Route::get('developers/{developer}', [DeveloperController::class, 'show'])->name('developers.show');
    Route::post('developers', [DeveloperController::class, 'store'])->name('developers.store');
    Route::put('developers/{developer}', [DeveloperController::class, 'update'])->name('developers.update');
    Route::delete('developers/{developer}', [DeveloperController::class, 'destroy'])->name('developers.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
