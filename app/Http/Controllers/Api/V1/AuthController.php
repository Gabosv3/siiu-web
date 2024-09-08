<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],['email.required' => 'El correo es requerido',  // Mensaje de error personalizado si el email es requerido
            'email.email' => 'Debe ser un correo válido',  // Mensaje de error personalizado si el email no es válido
            'password.required' => 'La contraseña es requerida',] );

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json([
            'message' => 'credenciales invalidas'
        ], 401);
    }

    public function logout(Request $request)
    {
        // Revocar el token que el usuario está usando para autenticarse
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Cierre de sesión exitoso.'
        ], 200);
    }

 
}
