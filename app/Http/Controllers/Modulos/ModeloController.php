<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\Models;
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
            'fabricante_id' => 'required|exists:manufacturers,id', // Aseguramos que el fabricante existe
        ]);

        // Creación del nuevo modelo
        $modelo = Models::create([
            'name' => $request->nombre, // Aquí usamos 'nombre' del request
            'manufacturer_id' => $request->fabricante_id, // Aquí usamos 'fabricante_id' del request
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
        $fabricante = Manufacturer::findOrFail($fabricante_id);
    
        // Obtener los modelos asociados al fabricante
        $modelos = Models::where('manufacturer_id', $fabricante_id)->get();
    
        // Devolver los modelos en formato JSON
        return response()->json([
            'modelos' => $modelos
        ], 200);
    }
}