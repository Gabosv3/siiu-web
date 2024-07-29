<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\LoginSecurity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class LoginSecurityController extends Controller
{
    /**
     * Crear una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el formulario de configuración de 2FA
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show2faForm(Request $request)
    {
        try {
            $user = Auth::user();
            $google2fa_url = "";
            $secret_key = "";

            // Verificar si el usuario ya tiene configurada la seguridad de inicio de sesión
            if ($user->loginSecurity()->exists()) {
                $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
                $google2fa_url = $google2fa->getQRCodeInline(
                    'Shopify App Local Demo', // Nombre de la aplicación
                    $user->email, // Email del usuario
                    $user->loginSecurity->google2fa_secret // Llave secreta de Google 2FA
                );
                $secret_key = $user->loginSecurity->google2fa_secret;
            }

            // Preparar los datos para la vista
            $data = [
                'user' => $user,
                'secret' => $secret_key,
                'google2fa_url' => $google2fa_url
            ];

            return view('Auth.2fa_settings', ['data' => $data]);
        } catch (Exception $e) {
            dd($e->getMessage().' '.$e->getLine()); // Mostrar el mensaje de error y la línea donde ocurrió
        }
    }

    /**
     * Generar la llave secreta de 2FA
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate2faSecret(Request $request)
    {
        try {
            $user = Auth::user();
            // Inicializar la clase de 2FA
            $google2fa = new Google2FA();

            // Agregar la llave secreta a los datos de registro
            $login_security = LoginSecurity::firstOrNew(['user_id' => $user->id]);
            $login_security->user_id = $user->id;
            $login_security->google2fa_enable = 0; // Desactivar 2FA inicialmente
            $login_security->google2fa_secret = $google2fa->generateSecretKey();
            $login_security->save();

            return redirect()->route('show2FASettings')->with('success', "Llave secreta generada.");
        } catch (Exception $e) {
            dd($e->getMessage().' '.$e->getLine()); // Mostrar el mensaje de error y la línea donde ocurrió
        }
    }

    /**
     * Habilitar 2FA
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable2fa(Request $request)
    {
        try {
            $user = Auth::user();
            $google2fa = new Google2FA();
            $secret = $request->input('secret');
            $valid = $google2fa->verifyKey($user->loginSecurity->google2fa_secret, $secret);

            if ($valid) {
                $user->loginSecurity->google2fa_enable = 1; // Habilitar 2FA
                $user->loginSecurity->save();
                return redirect()->route('show2FASettings')->with('success', "2FA habilitado exitosamente.");
            } else {
                return redirect()->route('show2FASettings')->with('error', "Código de verificación inválido, por favor intente nuevamente.");
            }
        } catch (Exception $e) {
            dd($e->getMessage().' '.$e->getLine()); // Mostrar el mensaje de error y la línea donde ocurrió
        }
    }

    /**
     * Deshabilitar 2FA
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable2fa(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // Las contraseñas no coinciden
            return back()->with("error", "Su contraseña no coincide con la de su cuenta. Por favor intente nuevamente.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
        ]);

        $user = Auth::user();
        $user->loginSecurity->google2fa_enable = 0; // Deshabilitar 2FA
        $user->loginSecurity->save();

        return redirect()->route('show2FASettings')->with('success', "2FA deshabilitado.");
    }

    /**
     * Verificar el código 2FA
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify2fa(Request $request)
    {
        // Lógica para verificar el código 2FA
        // ...

        // Redirige a la página anterior o a una ruta específica
        return redirect()->route('dashboard'); // Asegúrate de reemplazar con la ruta correcta
    }
}