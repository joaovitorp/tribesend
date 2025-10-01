<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriberGroupController;
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

// Public form routes - no authentication required
Route::get('/f/{hash}', [PublicFormController::class, 'show'])->name('public.forms.show');
Route::post('/f/{hash}/subscribe', [PublicFormController::class, 'subscribe'])->name('public.forms.subscribe');
Route::get('/f/{hash}/success', [PublicFormController::class, 'success'])->name('forms.success');

// Form management routes - authenticated users with teams
Route::middleware(['auth', 'verified', 'user.has.team'])->group(function () {
    Route::resource('forms', FormController::class);
    Route::resource('subscribers', SubscriberController::class);
    Route::resource('subscriber-groups', SubscriberGroupController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
