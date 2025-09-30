<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordCreationController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Cocina\Cocina;
use App\Livewire\Dashboard\AdminProductos;
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

Route::middleware(['auth', 'verified', 'role:admin,empleado'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/cocina', Cocina::class)->name('cocina');
    Route::get('/empleados', Empleados::class)->name('empleados');
    Route::get('/productos', AdminProductos::class)->name('productos');

});



// Ruta específica para clientes (no requiere email verificado)
Route::middleware(['auth'])->group(function () {
    Route::get('/cliente/bienvenida', App\Livewire\Cliente\ClienteBienvenida::class)->name('cliente.bienvenida');
    Route::get('/cliente/pedidos', App\Livewire\Cliente\MisPedidos::class)->name('cliente.pedidos');
    Route::get('/cliente/carrito/checkout', App\Livewire\Cliente\CarritoCheckout::class)->name('cliente.carrito.checkout');
    Route::get('/cliente/pago/procesando/{transaccionId}', App\Livewire\Cliente\ProcesoPago::class)->name('cliente.pago.procesando');
    Route::get('/cliente/pedido/{pedido}/confirmacion', App\Livewire\Cliente\PedidoConfirmacion::class)->name('cliente.pedido.confirmacion');

    // Mantener alias de ruta antigua para compatibilidad
    Route::get('/cliente/checkout', function () {
        return redirect()->route('cliente.carrito.checkout');
    })->name('cliente.checkout');
});

// Fallback route - redirect any unregistered route to login
Route::fallback(function () {
    return redirect()->route('login');
});
