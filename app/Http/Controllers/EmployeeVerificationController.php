<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmployeeVerificationController extends Controller
{
    /**
     * Verify the employee's email address.
     */
    public function verify(Request $request, $user)
    {
        // Verify the signed URL
        if (!URL::hasValidSignature($request)) {
            return view('employee.verification-failed', [
                'message' => 'El enlace de verificación es inválido o ha expirado.',
            ]);
        }

        // Find the user
        $employee = User::findOrFail($user);

        // Check if already verified
        if ($employee->hasVerifiedEmail()) {
            return view('employee.verification-success', [
                'message' => 'Tu cuenta ya había sido verificada anteriormente.',
                'employee' => $employee,
                'alreadyVerified' => true,
            ]);
        }

        // Mark email as verified
        $employee->markEmailAsVerified();

        return view('employee.verification-success', [
            'message' => '¡Tu cuenta ha sido verificada exitosamente!',
            'employee' => $employee,
            'alreadyVerified' => false,
        ]);
    }

    /**
     * Resend verification email.
     */
    public function resend(Request $request, $user)
    {
        $employee = User::findOrFail($user);

        if ($employee->hasVerifiedEmail()) {
            return redirect()->back()->with('error', 'Esta cuenta ya está verificada.');
        }

        // Resend verification notification
        $employee->notify(new \App\Notifications\WelcomeEmployeeNotification('password-no-disponible'));

        return redirect()->back()->with('message', 'Email de verificación reenviado correctamente.');
    }
}
