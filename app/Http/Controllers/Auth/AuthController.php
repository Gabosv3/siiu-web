<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Método para mostrar la vista de inicio de sesión
    public function login()
    {
        // Retorna la vista 'Auth.Login', donde está el formulario de inicio de sesión
        return view('auth.login');
    }

    // Método para verificar las credenciales de inicio de sesión
    public function loginVerify(Request $request)
    {
        // Validación de la solicitud
        $request->validate([
            'email' => 'required|email', // El campo email es requerido y debe ser un email válido
            'password' => 'required',    // El campo password es requerido
        ], [
            'email.required' => 'El correo es requerido',  // Mensaje de error personalizado si el email es requerido
            'email.email' => 'Debe ser un correo válido',  // Mensaje de error personalizado si el email no es válido
            'password.required' => 'La contraseña es requerida', // Mensaje de error personalizado si la contraseña es requerida
        ]);

        // Intentar autenticar al usuario con las credenciales proporcionadas
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Regenerar la sesión para evitar ataques de sesión fija
            $request->session()->regenerate();
            // Redirigir al usuario autenticado a la ruta 'dashboard'
            return redirect()->route('dashboard');
        }

        // Si la autenticación falla, redirigir de vuelta al formulario de inicio de sesión
        // con un mensaje de error y los datos de entrada
        return back()->withErrors(['invalid_credentials' => 'Usuario y contraseña inválidos'])->withInput();
    }

    // Método para cerrar la sesión
    public function signOut(Request $request)
    {
        // Cerrar la sesión del usuario
        Auth::logout();

        // Invalidar la sesión actual para evitar su reutilización
        $request->session()->invalidate();
        // Regenerar el token CSRF para proteger contra ataques CSRF
        $request->session()->regenerateToken();

        // Redirigir al usuario a la ruta 'login' con un mensaje de éxito
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }

}
