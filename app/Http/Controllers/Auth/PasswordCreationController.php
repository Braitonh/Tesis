<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordCreationController extends Controller
{
    public function show(string $token)
    {
        $user = User::where('verification_token', $token)->first();
        
        if (!$user) {
            return redirect('/')->with('error', 'Token de verificación inválido.');
        }
        
        if ($user->password_created) {
            return redirect('/login')->with('info', 'Tu contraseña ya ha sido creada. Puedes iniciar sesión.');
        }
        
        return view('auth.create-password', compact('user', 'token'));
    }
    
    public function store(Request $request, string $token)
    {
        $user = User::where('verification_token', $token)->first();
        
        if (!$user) {
            return redirect('/')->with('error', 'Token de verificación inválido.');
        }
        
        if ($user->password_created) {
            return redirect('/')->with('info', 'Tu contraseña ya ha sido creada.');
        }
        
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        $user->update([
            'password' => Hash::make($request->password),
            'password_created' => true,
        ]);
        
        $user->markEmailAsVerified();
        
        return redirect('/login')->with('success', '¡Contraseña creada exitosamente! Ya puedes iniciar sesión con tu email y nueva contraseña.');
    }
}
