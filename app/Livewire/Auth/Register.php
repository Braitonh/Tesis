<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    #[Rule('required|string|min:2|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:8|confirmed')]
    public string $password = '';

    #[Rule('required|min:8')]
    public string $password_confirmation = '';

    public bool $loading = false;

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser un texto válido.',
        'name.min' => 'El nombre debe tener al menos 2 caracteres.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Debe ser un correo electrónico válido.',
        'email.unique' => 'Este correo electrónico ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
        'password_confirmation.min' => 'La confirmación de contraseña debe tener al menos 8 caracteres.',
    ];

    public function register()
    {
        $this->loading = true;

        $key = 'register.'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            $this->addError('email', 'Demasiados intentos de registro. Inténtelo de nuevo en '.$seconds.' segundos.');
            $this->loading = false;

            return;
        }
        $this->validate();

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'cliente',
                'email_verified_at' => now(), // Clients are automatically verified
                'password_created' => true,
            ]);

            RateLimiter::clear($key);

            return redirect()->intended('/login');
        } catch (\Exception $e) {
            RateLimiter::hit($key, 60);
            $this->addError('email', 'Error al crear la cuenta. Inténtelo de nuevo.');
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.auth.register')->with([
            'backgroundGradient' => 'bg-gradient-to-br from-emerald-500 to-green-600',
        ]);
    }
}
