<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset form.
     */
    public function show(Request $request, string $token)
    {
        $email = $request->query('email');

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle the password reset request.
     */
    public function store(Request $request)
    {
        $messages = [
            'token.required' => 'El token es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ], $messages);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida exitosamente. Ya puedes iniciar sesión.');
        }

        // Traducir mensajes de error del sistema de reset de contraseña
        $errorMessages = [
            Password::INVALID_TOKEN => 'El token de restablecimiento es inválido o ha expirado.',
            Password::INVALID_USER => 'No encontramos un usuario con ese correo electrónico.',
            Password::RESET_THROTTLED => 'Demasiados intentos. Por favor, inténtelo de nuevo más tarde.',
        ];

        $errorMessage = $errorMessages[$status] ?? 'Ha ocurrido un error al restablecer la contraseña. Por favor, inténtelo de nuevo.';

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }
}

