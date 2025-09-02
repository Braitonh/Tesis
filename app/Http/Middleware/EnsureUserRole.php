<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Verificar si el usuario tiene uno de los roles permitidos
        if (!in_array($user->role, $roles)) {
            // Redireccionar según el rol del usuario
            if ($user->role === 'cliente') {
                return redirect()->route('cliente.bienvenida');
            }
            
            // Para otros roles no permitidos, redirigir al login
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
