<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        // Muestra el formulario de restablecimiento de contraseña.
        // Se pasa el token de restablecimiento y el correo electrónico del usuario a la vista.
        return view('authenticated.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        // Valida los datos enviados en la solicitud.
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // Debe contener al menos una letra minúscula
                'regex:/[A-Z]/',      // Debe contener al menos una letra mayúscula
                'regex:/[0-9]/',      // Debe contener al menos un número
                'regex:/[@$!%*?&]/',  // Debe contener al menos un símbolo
            ],
        ]);

        // Si la validación falla, redirige al usuario de vuelta al formulario con los errores y los datos ingresados.
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Intenta restablecer la contraseña usando el token proporcionado.
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Hash y guarda la nueva contraseña del usuario.
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        // Redirige según la respuesta del intento de restablecimiento de contraseña.
        if ($response == Password::PASSWORD_RESET) {
            // Si el restablecimiento fue exitoso, redirige al usuario al formulario de inicio de sesión con un mensaje de éxito.
            return redirect()->route('login')->with('status', __($response));
        } elseif ($response == Password::INVALID_TOKEN) {
            // Si el token es inválido o ha expirado, redirige al usuario de vuelta al formulario con un mensaje de error.
            return back()->withErrors(['token' => 'El token de restablecimiento de contraseña no es válido o ha expirado.']);
        } else {
            // Para cualquier otro error, redirige al usuario de vuelta al formulario con un mensaje de error.
            return back()->withErrors(['email' => __($response)]);
        }
    }
}
