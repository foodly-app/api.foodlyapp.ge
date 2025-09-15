<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\DatabaseTestController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Database test route (for easy browser access)
Route::get('/test/db', [DatabaseTestController::class, 'testConnection'])->name('test.db');

// API Documentation Routes
Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', function () {
        return view('docs.index');
    })->name('index');
    
    Route::get('/auth', function () {
        return view('docs.auth');
    })->name('auth');
    
    Route::get('/website', function () {
        return view('docs.website');
    })->name('website');
    
    Route::get('/android', function () {
        return view('docs.android');
    })->name('android');
    
    Route::get('/ios', function () {
        return view('docs.ios');
    })->name('ios');
    
    Route::get('/kiosk', function () {
        return view('docs.kiosk');
    })->name('kiosk');
    
    Route::get('/examples', function () {
        return view('docs.examples');
    })->name('examples');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});

require __DIR__.'/auth.php';
