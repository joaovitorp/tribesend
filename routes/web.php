<?php

use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'user.has.team'])->name('dashboard');

// Onboarding routes - apenas para usuários sem teams
Route::middleware(['auth', 'verified', 'user.has.no.team'])->group(function () {
    Route::get('/onboarding/team', [OnboardingController::class, 'create'])->name('onboarding.team.create');
    Route::post('/onboarding/team', [OnboardingController::class, 'store'])->name('onboarding.team.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
