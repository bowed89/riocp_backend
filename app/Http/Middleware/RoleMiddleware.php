<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, $roles): Response
    {
        $user = Auth::user();

        // obtengo roles '1.2' y los convierto en array separando con puntos
        $allowedRoles = array_map('intval', explode('.', $roles));
        
        // Verifica si el rol del usuario está dentro de los roles permitidos
        if (!$user || !in_array($user->rol_id, $allowedRoles)) {
            return response()->json([
                'status' => false,
                'message' => 'El rol no tiene acceso a esta consulta.',
                'message22' => $user->rol_id,
            ], 403);
        }

        return $next($request);
    }
}
