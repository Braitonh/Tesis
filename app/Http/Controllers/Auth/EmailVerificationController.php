<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\WelcomeUserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        $user = Auth::user();

        // Si ya está verificado, redirigir al dashboard
        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        return view('auth.verify-email', compact('user'));
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        // Solo reenviar si es un empleado que no ha creado contraseña
        if (!$user->password_created && 'cliente' !== $user->role) {
            // Regenerar token y reenviar email
            $token = $user->generateVerificationToken();
            $user->notify(new WelcomeUserNotification($user, $token));

            return back()->with('status', 'Email de verificación reenviado exitosamente.');
        }

        return back()->with('error', 'No se puede reenviar el email de verificación.');
    }
}
