<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    #[Rule('required|email:filter')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;
    public bool $loading = false;

    public function login()
    {
        $this->loading = true;

        $key = 'login.'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            $this->addError('email', 'Demasiados intentos de inicio de sesión. Inténtelo de nuevo en '.$seconds.' segundos.');
            $this->loading = false;

            return;
        }

        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($key);

            $user = Auth::user();

            // Verificar si el cliente está bloqueado ANTES de regenerar la sesión
            if ('cliente' === $user->role && $user->is_blocked) {
                // Hacer logout sin invalidar la sesión completa para evitar problemas con el token CSRF
                Auth::logout();
                
                $this->addError('email', 'Tu cuenta ha sido bloqueada. Por favor, contacta con el administrador para más información.');
                $this->loading = false;
                
                return;
            }

            // Verificar si el cliente ha verificado su email
            if ('cliente' === $user->role && !$user->hasVerifiedEmail()) {
                // Hacer logout sin invalidar la sesión completa para evitar problemas con el token CSRF
                Auth::logout();
                
                $this->addError('email', 'Debes verificar tu correo electrónico antes de iniciar sesión. Por favor, revisa tu bandeja de entrada y haz clic en el enlace de activación.');
                $this->loading = false;
                
                return;
            }

            // Solo regenerar la sesión si el usuario no está bloqueado y está verificado (para clientes)
            session()->regenerate();

            // Redireccionar según el rol del usuario
            if ('cliente' === $user->role) {
                return redirect()->intended('/cliente/bienvenida');
            } elseif ('delivery' === $user->role) {
                return redirect()->intended('/delivery');
            } elseif ('cocina' === $user->role) {
                return redirect()->intended('/cocina');
            } elseif ('ventas' === $user->role) {
                return redirect()->intended('/pedidos');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        RateLimiter::hit($key, 60);

        $this->addError('email', 'Las credenciales proporcionadas son incorrectas.');
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.auth.login')->with([
        ]);
    }
}
