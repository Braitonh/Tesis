<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class ForgotPassword extends Component
{
    #[Rule('required|email:filter')]
    public string $email = '';

    public bool $loading = false;
    public bool $emailSent = false;

    protected $messages = [
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Debe ser un correo electrónico válido.',
    ];

    public function sendResetLink()
    {
        $this->loading = true;

        $key = 'forgot-password.'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            $this->addError('email', 'Demasiados intentos. Inténtelo de nuevo en '.$seconds.' segundos.');
            $this->loading = false;

            return;
        }

        $this->validate();

        try {
            // Buscar el usuario por email
            $user = User::where('email', $this->email)->first();

            // Por seguridad, siempre mostramos el mismo mensaje
            // para no revelar si el email existe o no
            if ($user) {
                // Generar token de reset usando el sistema de Laravel
                $token = Password::createToken($user);
                
                // Enviar notificación directamente como en el registro
                $user->notify(new ResetPasswordNotification($token));
            }

            RateLimiter::hit($key, 60);

            $this->emailSent = true;
            session()->flash('status', 'Recibirás un enlace de restablecimiento a tu correo electrónico.');
        } catch (\Exception $e) {
            RateLimiter::hit($key, 60);
            // Por seguridad, mostramos el mismo mensaje incluso si hay error
            $this->emailSent = true;
            session()->flash('status', 'Recibirás un enlace de restablecimiento a tu correo electrónico.');
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}

