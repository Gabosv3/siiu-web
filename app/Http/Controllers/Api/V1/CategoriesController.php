<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\HardwareResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * Muestra una lista de las categorías.
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
            ], 401); // 401
        }

        try {
            // Obtener todas las categorías
            $categories = Category::all();
            return CategoryResource::collection($categories);
        } catch (\Exception $e) {
            // Manejar cualquier excepción inesperada y retornar un mensaje de error genérico
            return response()->json([
                'error' => 'Ocurrió un error inesperado al recuperar las categorías.',
                'message' => $e->getMessage()
            ], 500); // 500
        }
    }

    /**
     * Muestra una categoría específica por su ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // Validar que el ID sea un número
        if($id == null || !is_numeric($id)){
            return response()->json([
                'error' => 'ID inválido.',
                'message' => 'El ID del categoría es inválido.'
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
            // Intentar encontrar la categoría solicitada por su ID
            $category = Category::findOrFail($id);
            return new CategoryResource($category);
        } catch (ModelNotFoundException $e) {
            // Si la categoría con el ID especificado no existe, retornar un mensaje de error
            return response()->json([
                'error' => 'Categoría no encontrada.',
                'message' => 'No se encontró ninguna categoría con el ID especificado.'
            ], Response::HTTP_NOT_FOUND); // 404
        } catch (\Exception $e) {
            // Manejar cualquier otra excepción inesperada
            return response()->json([
                'error' => 'Ocurrió un error inesperado al recuperar la categoría.',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function getEquipmentsByCategory(Request $request, $id)
{
    // Validar que el ID sea un número
    if ($id == null || !is_numeric($id)) {
        return response()->json([
            'error' => 'ID inválido.',
            'message' => 'El ID de la categoría es inválido.'
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
        // Intentar encontrar la categoría solicitada por su ID
        $category = Category::findOrFail($id);

        // Cargar los equipos asociados a la categoría
        $equipments = HardwareResource::collection($category->equipments);

        return response()->json($equipments);
    } catch (ModelNotFoundException $e) {
        // Si la categoría con el ID especificado no existe, retornar un mensaje de error
        return response()->json([
            'error' => 'Categoría no encontrada.',
            'message' => 'No se encontró ninguna categoría con el ID especificado.'
        ], Response::HTTP_NOT_FOUND); // 404
    } catch (\Exception $e) {
        // Manejar cualquier otra excepción inesperada
        return response()->json([
            'error' => 'Ocurrió un error inesperado al recuperar los equipos.',
            'message' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
    }
}

}
