<?php

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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

    Route::get('/cocina', Cocina::class)->name('cocina');
    Route::get('/empleados', Empleados::class)->name('empleados');
    
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/login');
    })->name('logout');
});

// Fallback route - redirect any unregistered route to login
Route::fallback(function () {
    return redirect()->route('login');
});
