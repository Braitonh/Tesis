<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->hasVerifiedEmail()) {
            // Permitir acceso a páginas de verificación y creación de contraseña
            if ($request->routeIs('verification.notice')
                || $request->routeIs('verification.resend')
                || $request->routeIs('password.show')
                || $request->routeIs('password.create')
                || $request->routeIs('cliente.verify')) {
                return $next($request);
            }

            // Redirigir a la página de verificación pendiente
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
