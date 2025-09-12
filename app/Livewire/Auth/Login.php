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
    #[Rule('required|email')]
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

            session()->regenerate();

            $user = Auth::user();

            // Redireccionar según el rol del usuario
            if ('cliente' === $user->role) {
                return redirect()->intended('/cliente/bienvenida');
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
