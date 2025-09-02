<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && !$user->hasVerifiedEmail()) {
            // Los usuarios cliente no requieren verificación de email
            if ($user->role === 'cliente') {
                return $next($request);
            }
            
            // Permitir acceso a páginas de verificación y creación de contraseña
            if ($request->routeIs('verification.notice') || 
                $request->routeIs('verification.resend') || 
                $request->routeIs('password.show') || 
                $request->routeIs('password.create')) {
                return $next($request);
            }
            
            // Redirigir a la página de verificación pendiente
            return redirect()->route('verification.notice');
        }
        
        return $next($request);
    }
}
