<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Fabricante;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    // Crear nuevo modelo
    public function store(Request $request)
    {
       // Método para crear un nuevo modelo
    
        // Validación de los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fabricante_id' => 'required|exists:fabricantes,id', // Aseguramos que el fabricante existe
        ]);

        // Creación del nuevo modelo
        $modelo = Modelo::create([
            'nombre' => $request->nombre,
            'fabricante_id' => $request->fabricante_id,
        ]);

        // Devolver respuesta en formato JSON
        return response()->json([
            'message' => 'Modelo creado con éxito',
            'modelo' => $modelo
        ], 201);
    }

    // Método para obtener los modelos por fabricante
    public function getModelosPorFabricante($fabricante_id)
    {
        // Validación para asegurarse de que el fabricante existe
        $fabricante = Fabricante::findOrFail($fabricante_id);

        // Obtener los modelos asociados al fabricante
        $modelos = Modelo::where('fabricante_id', $fabricante_id)->get();

        // Devolver los modelos en formato JSON
        return response()->json([
            'modelos' => $modelos
        ], 200);
    }
}