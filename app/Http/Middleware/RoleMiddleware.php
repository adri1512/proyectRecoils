<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = auth()->user();

        if (!$user || $user->role !== $role) {
            if ($user && $user->role === 'cliente') {
                return redirect()->route('client_home');
            }

            if ($user && $user->role === 'conductor') {
                return redirect()->route('driver_home');
            }

            if ($user && $user->role === 'logistico') {
                return redirect()->route('logistic_home');
            }

            return redirect('/');
        }

        return $next($request);
    }
}
