<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            // Obtener todos los usuarios
            $users = User::all();
            // Devolver los usuarios en formato JSON
            return response()->json($users);
        } else {
            // Registrar el intento fallido
            Log::warning('Token inválido o usuario no autenticado', [
                'token' => $request->header('Authorization'),
            ]);
            // Retorna un error de autenticación si el token no es válido
            return response()->json(['error' => 'No Autorizado'], 401);
        }
    }

 
}
