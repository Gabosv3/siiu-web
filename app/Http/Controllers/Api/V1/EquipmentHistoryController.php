<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentHistoryResource;
use App\Models\EquipmentHistory;
use App\Models\Hardware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentHistoryController extends Controller
{
 

    

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
            // Obtener todos los registros de historial de equipos junto con el equipo y el usuario relacionados
            $history = EquipmentHistory::with(['equipment', 'user'])->get();
            return EquipmentHistoryResource::collection($history);
        } catch (\Exception $e) {
            // Manejar cualquier excepción inesperada y retornar un mensaje de error
            return response()->json([
                'error' => 'Ocurrió un error inesperado al recuperar los historiales de equipos.',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    /**
     * Muestra un registro específico de historial de equipo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // Validar que el ID sea un número
        if ($id == null || !is_numeric($id)) {
            return response()->json([
                'error' => 'ID inválido.',
                'message' => 'El ID del historial es inválido.'
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
            // Intentar encontrar el historial de equipo solicitado por su ID
            $history = EquipmentHistory::with(['equipment', 'user'])->findOrFail($id);
            return new EquipmentHistoryResource($history);
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra el historial con el ID especificado, retornar un mensaje de error
            return response()->json([
                'error' => 'Historial de equipo no encontrado.',
                'message' => 'No se encontró ningún registro de historial de equipo con el ID especificado.'
            ], Response::HTTP_NOT_FOUND); // 404

        } catch (\Exception $e) {
            // Manejar cualquier otra excepción inesperada
            return response()->json([
                'error' => 'Ocurrió un error inesperado al recuperar el registro de historial de equipo.',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }
    public function EquipmentHistory($categoryId, $inventoryCode)
    {
        // Validar que los parámetros sean números enteros positivos
        if (!ctype_digit($categoryId)  || $categoryId <= 0) {
            return response()->json(['error' => 'Los parámetros deben ser números enteros positivos'], 400); // Código 400 para solicitud incorrecta
        }
    
        // Filtramos el hardware por la categoría y el código de inventario
        $hardware = Hardware::where('category_id', $categoryId)
            ->where('inventory_code', $inventoryCode)
            ->with(['hardwareAssigned.user.departament', 'equipmentHistories']) // Cargar la relación de historiales
            ->first();
    
        // Si no se encuentra el hardware, devolver un error 404
        if (!$hardware) {
            return response()->json(['error' => 'No se encontró el equipo con el código de inventario especificado'], 404);
        }
    
        // Devolvemos el hardware junto con sus historiales asociados
        return response()->json([
            'hardware' => $hardware
        ]);
    }
    
}
