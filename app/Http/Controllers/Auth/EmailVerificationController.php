<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\ClienteVerificationNotification;
use App\Notifications\WelcomeUserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        $user = Auth::user();

        // Si ya está verificado, redirigir según el rol
        if ($user->hasVerifiedEmail()) {
            if ('cliente' === $user->role) {
                return redirect()->route('cliente.bienvenida');
            }

            return redirect('/dashboard');
        }

        return view('auth.verify-email', compact('user'));
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            if ('cliente' === $user->role) {
                return redirect()->route('cliente.bienvenida');
            }

            return redirect('/dashboard');
        }

        // Reenviar email de verificación para clientes
        if ('cliente' === $user->role) {
            $token = $user->generateVerificationToken();
            $user->notify(new ClienteVerificationNotification($user, $token));

            return back()->with('status', 'Email de verificación reenviado exitosamente.');
        }

        // Reenviar email para empleados que no han creado contraseña
        if (!$user->password_created && 'cliente' !== $user->role) {
            // Regenerar token y reenviar email
            $token = $user->generateVerificationToken();
            $user->notify(new WelcomeUserNotification($user, $token));

            return back()->with('status', 'Email de verificación reenviado exitosamente.');
        }

        return back()->with('error', 'No se puede reenviar el email de verificación.');
    }
}
