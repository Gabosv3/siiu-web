<?php

namespace App\Http\Middleware;

use App\Support\Google2FAAuthenticator;
use Closure;


class LoginSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // Inicializar el autenticador de Google 2FA con la solicitud actual
        $authenticator = app(Google2FAAuthenticator::class)->boot($request);

        // Verificar si el usuario está autenticado con 2FA
        if ($authenticator->isAuthenticated()) {
            // Si el usuario está autenticado, continuar con la siguiente solicitud
            return $next($request);
        }

        // Si el usuario no está autenticado, redirigirlo para que ingrese el código 2FA
        return $authenticator->makeRequestOneTimePasswordResponse();
    }

}
