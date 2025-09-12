<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, \Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar si el usuario tiene uno de los roles permitidos
        if (!in_array($user->role, $roles)) {
            // Redireccionar según el rol del usuario
            if ('cliente' === $user->role) {
                return redirect()->route('cliente.bienvenida');
            }

            // Para otros roles no permitidos, redirigir al login
            return redirect()->route('login');
        }

        return $next($request);
    }
}
