<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Characteristic;
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
        return view('inventories.models.index', compact('models', 'deletedModels'));
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
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.string' => 'El nombre debe ser una cadena de texto',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres',
            'fabricante_id.required' => 'El fabricante es obligatorio',
            'fabricante_id.exists' => 'El fabricante no existe',
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
    // Cambia 'modelCharacteristic' por 'modelCharacteristics'
    $model = Models::with('modelCharacteristics')->findOrFail($id);

    if ($model->modelCharacteristics->isEmpty()) {
        // Manejar el caso sin características
        return view('inventories.models.show', ['model' => $model, 'message' => 'No hay características disponibles']);
    }

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

        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no debe superar los 255 caracteres',

        ]);

        // Actualizar el modelo
        $modelo = Models::findOrFail($id);
        $modelo->update([
            'name' => $request->name,
        ]);

        return redirect()->route('models.index')->with('success', 'Modelo actualizado exitosamente');
    }


    public function destroy($id)
    {
        $model = Models::findOrFail($id);
        $model->delete();

        if ($model) {
            return redirect()->route('models.index')->with('success', 'Modelo eliminado exitosamente');
        }

        return redirect()->route('models.index')->with('error', 'Modelo no encontrado');
    }

    public function restore($id)
    {
        $model = Models::withTrashed()->findOrFail($id);
        $model->restore();
        if ($model) {
            return redirect()->route('models.index')->with('success', 'Modelo restaurado exitosamente');
        }
        return redirect()->route('models.index')->with('error', 'Modelo no encontrado');
    }

    public function associateCharacteristics($id)
{
    $model = Models::findOrFail($id); // Encontrar el modelo
    $caracteristicas = Characteristic::all(); // Listar todas las características

    return view('inventories.models.associate', compact('model', 'caracteristicas'));
}

    public function storeCharacteristics(Request $request, $id)
    {
        $request->validate([
            'caracteristicas' => 'array|required', // Validar que sea un arreglo
            'caracteristicas.*.id' => 'exists:characteristics,id', // Validar que las características existan
            'caracteristicas.*.valor' => 'required|string|max:255', // Validar los valores
        ]);

        $model = Models::findOrFail($id);

        foreach ($request->caracteristicas as $caracteristica) {
            $model->modelCharacteristic()->updateOrCreate(
                ['characteristic_id' => $caracteristica['id']], // Busca por characteristic_id
                ['value' => $caracteristica['valor']] // Crea o actualiza el valor
            );
        }

        return redirect()->route('models.show', $id)->with('success', 'Características asociadas exitosamente');
    }
}
