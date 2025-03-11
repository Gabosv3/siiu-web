<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\CustomResetPassword;
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
    // Validar el correo electrónico
    $request->validate([
        'email' => 'required|email',
    ]);

    // Verificar si el usuario existe
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => __('passwords.user')]); // Mensaje de usuario no encontrado
    }

    // Generar el token manualmente
    $token = app('auth.password.broker')->createToken($user);

    // Construir la URL para restablecer la contraseña
    $url = url(route('password.reset', ['token' => $token, 'email' => $request->email], false));

    // Enviar el correo de restablecimiento de contraseña con la notificación personalizada
    $user->notify(new CustomResetPassword($token));

    // Redirigir con un mensaje de éxito
    return back()->with('status', __('passwords.sent'));
}

}
