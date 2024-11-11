<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Maneja una solicitud entrante y verifica si el usuario tiene privilegios de administrador
     *
     * Este middleware realiza lo siguiente:
     * - Comprueba si el usuario está autenticado mediante `auth()->check()`
     * - Verifica si el usuario autenticado tiene permisos de administrador mediante `auth()->user()->is_admin`
     * - Si el usuario no está autenticado o no tiene privilegios de administrador, devuelve un error 403 (prohibido)
     * - Si el usuario pasa las verificaciones, permite que la solicitud continue
     *
     * @param \Illuminate\Http\Request $request La solicitud entrante
     * @param \Closure $next La siguiente acción que se ejecutará si el usuario cumple con las condiciones
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse La respuesta generada por la aplicación después de la verificación
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}
