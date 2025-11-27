<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteVerificationController extends Controller
{
    /**
     * Verify the client's email address using token.
     */
    public function verify($token)
    {
        // Buscar usuario por token de verificación
        $user = User::where('verification_token', $token)
            ->where('role', 'cliente')
            ->first();

        if (!$user) {
            return view('auth.cliente-verification-failed', [
                'message' => 'El enlace de verificación es inválido o ha expirado.',
            ]);
        }

        // Verificar si ya está verificado
        if ($user->hasVerifiedEmail()) {
            return view('auth.cliente-verification-success', [
                'message' => 'Tu cuenta ya había sido verificada anteriormente.',
                'user' => $user,
                'alreadyVerified' => true,
            ]);
        }

        // Marcar email como verificado
        $user->markEmailAsVerified();

        return view('auth.cliente-verification-success', [
            'message' => '¡Tu cuenta ha sido verificada exitosamente!',
            'user' => $user,
            'alreadyVerified' => false,
        ]);
    }
}


