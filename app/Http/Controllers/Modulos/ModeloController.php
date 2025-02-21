<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Characteristic;
use App\Models\Manufacturer;
use App\Models\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            'success' => true,
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
        $model = Models::with('characteristics')->findOrFail($id); // Cargar el modelo junto con las características asociadas
        $characteristics = Characteristic::all(); // Obtener todas las características disponibles

        return view('inventories.models.edit', compact('model', 'characteristics'));
    }

    public function update(Request $request, $id)
{
    try {
        // Validación de los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255',
            'characteristics_values' => 'array', // Aseguramos que sea un array
            'characteristics_values.*' => 'nullable|string', // Cada valor puede ser nulo o una cadena
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

        // Actualizar las características
        if ($request->has('characteristics_values')) {
            foreach ($request->characteristics_values as $characteristic_id => $value) {
                // Encontrar la característica en la tabla pivote y actualizar su valor
                $modelo->characteristics()->updateExistingPivot($characteristic_id, [
                    'value' => $value,
                ]);
            }
        }

        // Si todo es exitoso, retornar la respuesta
        return redirect()->route('models.edit', $id)
                         ->with('success', 'Modelo actualizado exitosamente');
    } catch (\Exception $e) {
        // Si ocurre un error, retornar con el mensaje de error
        return redirect()->route('models.edit', $id)
                         ->with('error', 'Ocurrió un error al actualizar el modelo: ' . $e->getMessage());
    }
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

    public function addCharacteristic(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'characteristic_id' => 'required|exists:characteristics,id',
            'value' => 'required|string|max:255',
        ]);

        $model = Models::findOrFail($id);

        // Verificar si la relación ya existe en la tabla pivote
        $exists = $model->characteristics()
            ->wherePivot('characteristic_id', $request->characteristic_id)
            ->wherePivot('value', $request->value)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Esta característica ya existe para este modelo con el mismo valor.',
            ], 422); // 422 Unprocessable Entity
        }

        // Crear la relación en la tabla pivote
        $model->characteristics()->attach($request->characteristic_id, ['value' => $request->value]);

        // Obtener la característica para responder con información adicional
        $characteristic = Characteristic::findOrFail($request->characteristic_id);

        // Devolver la respuesta para AJAX
        return response()->json([
            'id' => $characteristic->id,
            'name' => $characteristic->name,
            'value' => $request->value,
        ]);
    }

    // Asignar la característica al modelo

    public function removeCharacteristic($modelId, $characteristicId)
{
    try {
        $model = Models::findOrFail($modelId);

        // Eliminar la relación en la tabla pivote
        $model->characteristics()->detach($characteristicId);

        return response()->json(['message' => 'Característica eliminada correctamente.'], 200);
    } catch (\Exception $e) {

        return response()->json(['message' => 'No se pudo eliminar la característica.'], 500);
    }
}


}
