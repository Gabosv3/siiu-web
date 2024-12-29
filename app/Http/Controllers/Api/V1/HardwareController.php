<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\HardwareResource;
use App\Models\Hardware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HardwareController extends Controller
{
    /**
     * Muestra una lista de hardware con sus categorías.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'error' => 'No autorizado.',
                'message' => 'El usuario no está autenticado.'
            ], Response::HTTP_UNAUTHORIZED); // 401
        }

        try {
            // Cargar todos los registros de hardware junto con la categoría relacionada
            $hardware = Hardware::with('category')->get();
            
            // Retornar la colección de hardware usando el recurso, asegurando una respuesta estructurada
            return HardwareResource::collection($hardware);

        } catch (\Exception $e) {
            // Manejar cualquier excepción inesperada y retornar un mensaje de error genérico
            return response()->json([
                'error' => 'Ocurrió un error inesperado al recuperar los registros de hardware.',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    /**
     * Muestra el hardware especificado por su ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        // Validar que el ID sea un número
        if($id == null || !is_numeric($id)){
            return response()->json([
                'error' => 'ID inválido.',
                'message' => 'El ID del hardware es inválido.'
            ], Response::HTTP_BAD_REQUEST); // 400
        }

        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'error' => 'No autorizado.',
                'message' => 'El usuario no está autenticado.'
            ], Response::HTTP_UNAUTHORIZED); // 401
        }

        try {
            // Intentar encontrar el hardware solicitado por su ID
            $hardware = Hardware::with('category')->findOrFail($id);
            
            // Si se encuentra, se retorna en el formato definido por el HardwareResource
            return new HardwareResource($hardware);

        } catch (ModelNotFoundException $e) {
            // Si el hardware con el ID especificado no existe, retornar un mensaje de error
            return response()->json([
                'error' => 'Hardware no encontrado.',
                'message' => 'No se encontró ningún registro de hardware con el ID especificado.'
            ], Response::HTTP_NOT_FOUND); // 404

        } catch (\Exception $e) {
            // Manejar cualquier otra excepción inesperada
            return response()->json([
                'error' => 'Ocurrió un error inesperado al recuperar el registro de hardware.',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }
}
