<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        // Muestra el formulario para solicitar el enlace de restablecimiento de contraseña.
        return view('authenticated.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Valida los datos del formulario. En este caso, solo el campo 'email' es requerido y debe ser una dirección de correo válida.
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Si la validación falla, redirige de nuevo al formulario con los errores y los datos ingresados.
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Intenta enviar el enlace de restablecimiento de contraseña al correo proporcionado.
        $response = Password::sendResetLink(
            $request->only('email')
        );

        // Si el enlace se envía correctamente, redirige de vuelta con un mensaje de éxito.
        // Si ocurre algún error, redirige de vuelta con un mensaje de error.
        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', __($response))
            : back()->withErrors(['email' => __($response)]);
    }
}
