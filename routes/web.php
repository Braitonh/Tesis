<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordCreationController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Cocina\Cocina;
use App\Livewire\Empleados\Empleados;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Password creation routes - accessible without authentication
Route::get('/create-password/{token}', [PasswordCreationController::class, 'show'])->name('password.show');
Route::post('/create-password/{token}', [PasswordCreationController::class, 'store'])->name('password.create');

// Email verification routes (authenticated but not necessarily verified)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->name('verification.resend');
    
    // Logout route - available for authenticated users even if not verified
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('login');
    })->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

    Route::get('/cocina', Cocina::class)->name('cocina');
    Route::get('/empleados', Empleados::class)->name('empleados');
});

// Fallback route - redirect any unregistered route to login
Route::fallback(function () {
    return redirect()->route('login');
});
