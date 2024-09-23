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
    public function handle(Request $request, Closure $next, $rol): Response
    {
        $user = Auth::user();

        if (!$user || $user->rol_id !== (int)$rol) {
            return response()->json([
                'status' => false,
                'message' => 'El rol no tiene acceso a esta consulta.'
            ], 403);
        }

        return $next($request);
    }
}
