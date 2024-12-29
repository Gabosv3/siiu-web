<?php

namespace App\Http\Controllers\Auth;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    // Método para mostrar la vista de inicio de sesión
    public function login(Request $request)
    {
        // Recuperar el correo y la contraseña de las cookies si existen
        $email = $request->cookie('remember_email');
        $password = $request->cookie('remember_password');

        // Retorna la vista 'authenticated.login', pasando los datos de las cookies
        return view('authenticated.login', compact('email', 'password'));
    }
    // Método para verificar las credenciales de inicio de sesión
    public function loginVerify(Request $request)
    {

        // Validación de la solicitud
        $request->validate([
            'email' => 'required|email',
            'password' => 'required_if:remember,0',
        ], [
            'email.required' => 'El correo es requerido',
            'email.email' => 'Debe ser un correo válido',
            'password.required' => 'La contraseña es requerida si no seleccionas "Recordar sesión".',
        ]);

        // Intentar autenticar al usuario con "Recordar sesión" si está marcado
        $credentials = ['email' => $request->email, 'password' => $request->password];
        $remember = $request->has('remember'); // Verifica si la casilla de recordar está marcada

        if (Auth::attempt($credentials, $remember)) {
            // Regenerar la sesión para evitar ataques de sesión fija
            $request->session()->regenerate();

            if ($remember) {
                Cookie::queue('remember_email', $request->email, 120); // 120 minutos
                Cookie::queue('remember_password', $request->password, 120); // 120 minutos
            }

            // Disparar el evento después de la autenticación exitosa
            $userId = Auth::id(); // ID del usuario autenticado
            $data = ['message' => 'Usuario autenticado correctamente'];
            event(new MyEvent($data, $userId));

            // Redirigir al usuario autenticado a la ruta 'dashboard'
            return redirect()->route('dashboard');
        }

        // Si la autenticación falla, redirigir de vuelta con mensaje de error
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
