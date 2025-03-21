<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            // Agregar el mensaje a la sesión
            return response()->view('layouts.Errors.403', ['message' => 'No tienes permiso para realizar esta acción.'], 403);
        }

        if ($exception instanceof TokenMismatchException) {
            // Redirige al usuario a la página de inicio si el token CSRF ha expirado
            return redirect()->route('login')->with('message', 'Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.');
        }

        return parent::render($request, $exception);
    }
}
