<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\Models;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    // Obtener todos los modelos
    public function index()
    {
        // Obtener todos los modelos
        $models = Models::all();
        $deletedModels = Models::onlyTrashed()->get();
    
        // Devolver respuesta en formato JSON
        return view('inventories.models.index', compact('models','deletedModels'));
    }

    // Mostrar el formulario para crear un nuevo modelo
    public function create()
    {
        $fabricantes = Manufacturer::all();
        return view('inventories.models.create', compact('fabricantes'));
    }
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


    public function show($id)
    {
        $model = Models::findOrFail($id);
        return view('inventories.models.show', compact('model'));
    }

    public function edit($id)
    {
        $model = Models::findOrFail($id);
        return view('inventories.models.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255',
            'manufacturer_id' => 'required|exists:manufacturers,id', // Aseguramos que el fabricante existe
        ]);

        // Actualizar el modelo
        $modelo = Models::findOrFail($id);
        $modelo->update([
            'name' => $request->name,
            'manufacturer_id' => $request->manufacturer_id,
        ]);

        return redirect()->route('models.index')->with('success', 'Modelo actualizado exitosamente');
    }


    public function destroy($id)
    {
        $model = Models::findOrFail($id);
        $model->delete();
        
        if($model){
            return redirect()->route('models.index')->with('success', 'Modelo eliminado exitosamente');
        }

        return redirect()->route('models.index')->with('error', 'Modelo no encontrado');
    }

    public function restore($id)
    {
        $model = Models::withTrashed()->findOrFail($id);
        $model->restore();
       if($model){
            return redirect()->route('models.index')->with('success', 'Modelo restaurado exitosamente');
        }
        return redirect()->route('models.index')->with('error', 'Modelo no encontrado');
    }


}