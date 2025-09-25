<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectToCorrectPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // ✅ Si no hay usuario logueado
        if (! $user) {
            // Evitar bucle: dejar pasar si ya está en login, registro o reset
            if ($request->is('app/login*') || $request->is('app/register*') || $request->is('app/password*')) {
                return $next($request);
            }

            return redirect('/app/login');
        }

        // ✅ Si hay usuario, redirigir al panel correspondiente solo si no está ya ahí
        if ($user->is_admin && ! $request->is('admin*')) {
            return redirect('/admin');
        }

        if ($user->investigador && ! $request->is('investigadorpanel*')) {
            return redirect('/investigadorpanel');
        }

        if (! $user->is_admin && ! $user->investigador && ! $request->is('app*')) {
            return redirect('/app');
        }

        return $next($request);
    }
}
