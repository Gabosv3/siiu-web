<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Characteristic;
use App\Models\Manufacturer;
use App\Models\Models;
use Illuminate\Http\Request;


class ModeloController extends Controller
{
    /**
     * Constructor del controlador.
     *
     * Establece middleware para controlar los permisos de acceso a los métodos
     * del controlador. Los middleware se aplican a los métodos según se indica
     * a continuación:
     *
     * - index: can:modelos.index
     * - create y store: can:modelos.create
     * - edit y update: can:modelos.edit
     * - destroy: can:modelos.destroy
     * - restore: can:modelos.restore
     *
     * 
     public function __construct()
    {
       $this->middleware('can:modelos.index')->only('index');
       $this->middleware('can:modelos.create')->only('create', 'store');
        $this->middleware('can:modelos.edit')->only('edit', 'update');
        $this->middleware('can:modelos.destroy')->only('destroy');
        $this->middleware('can:modelos.restore')->only('restore');
    }-*/
    /**
     * Muestra una lista de todos los modelos con sus características.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener todos los modelos
        $models = Models::all();
        $deletedModels = Models::onlyTrashed()->get();

        // Devolver respuesta en formato JSON
        return view('inventories.models.index', compact('models', 'deletedModels'));
    }


    /**
     * Muestra el formulario para crear un nuevo modelo.
     *
     * Recibe un objeto Manufacturer como parámetro y utiliza el método compact para
     * pasar los datos a la vista. La vista create.blade.php utiliza el fabricante para
     * mostrar los datos del fabricante en un select.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $fabricantes = Manufacturer::all();
        return view('inventories.models.create', compact('fabricantes'));
    }


    /**
     * Crea un nuevo modelo en la base de datos.
     *
     * Valida los datos del formulario y crea un nuevo modelo con los datos
     * proporcionados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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


    /**
     * Obtiene los modelos asociados a un fabricante.
     *
     * Hace una petición GET a /modelos/por-fabricante/{fabricante_id} y devuelve
     * los modelos asociados al fabricante en formato JSON.
     *
     * @param int $fabricante_id ID del fabricante
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModelosPorFabricante($fabricante_id)
    {
        // Obtener los modelos asociados al fabricante
        $modelos = Models::where('manufacturer_id', $fabricante_id)->get();

        // Devolver los modelos en formato JSON
        return response()->json([
            'modelos' => $modelos
        ], 200);
    }


    /**
     * Muestra las características de un modelo específico.
     *
     * Busca el modelo por su ID y carga sus características asociadas.
     * Si no hay características disponibles, se devuelve una vista con un mensaje
     * indicando la ausencia de características.
     *
     * @param int $id ID del modelo
     * @return \Illuminate\View\View
     */

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

    /**
     * Muestra el formulario de edición de un modelo específico.
     *
     * Busca el modelo por su ID y carga sus características asociadas. Luego, pasa
     * el modelo y todas las características disponibles a la vista de edición.
     *
     * @param int $id ID del modelo
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = Models::with('characteristics')->findOrFail($id); // Cargar el modelo junto con las características asociadas
        $characteristics = Characteristic::all(); // Obtener todas las características disponibles

        return view('inventories.models.edit', compact('model', 'characteristics'));
    }

    /**
     * Actualiza un modelo existente en la base de datos.
     *
     * Este método valida los datos proporcionados en el request y actualiza el nombre
     * del modelo y sus características asociadas. Si las características son proporcionadas,
     * se actualizan sus valores en la tabla pivote.
     * En caso de éxito, redirige a la vista de edición del modelo con un mensaje de éxito.
     * Si ocurre un error, redirige con un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del modelo a actualizar.
     * @param int $id El ID del modelo que se va a actualizar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la vista de edición del modelo.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validación de los datos recibidos
            $request->validate([
                'name' => 'required|string|max:255', // Validación de los datos recibidos
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
            //Si todo es exitoso, retornar la respuesta
            return redirect()->route('models.edit', $id)->with('success', 'Modelo actualizado exitosamente'); 
        } catch (\Exception $e) {
            // Si ocurre un error, retornar con el mensaje de error
            return redirect()->route('models.edit', $id)
                ->with('error', 'Ocurrió un error al actualizar el modelo: ' . $e->getMessage());
        }
    }



    /**
     * Elimina un modelo existente de la base de datos.
     *
     * Busca el modelo por su ID y lo elimina, redirigiendo a la lista de modelos
     * con un mensaje de éxito si se elimina correctamente, o un mensaje de error
     * si el modelo no se encuentra.
     *
     * @param int $id El ID del modelo a eliminar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la lista de modelos.
     */

    public function destroy($id)
    {
        $model = Models::findOrFail($id);
        $model->delete();

        if ($model) {
            return redirect()->route('models.index')->with('success', 'Modelo eliminado exitosamente');
        }

        return redirect()->route('models.index')->with('error', 'Modelo no encontrado');
    }

    /**
     * Restaura un modelo eliminado de la base de datos.
     *
     * Busca el modelo eliminado por su ID y lo restaura.
     * Si se restaura con éxito, redirige a la lista de modelos con un mensaje de éxito.
     * Si el modelo no se encuentra, redirige con un mensaje de error.
     *
     * @param int $id El ID del modelo a restaurar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la lista de modelos.
     */

    public function restore($id)
    {
        $model = Models::withTrashed()->findOrFail($id);
        $model->restore();
        if ($model) {
            return redirect()->route('models.index')->with('success', 'Modelo restaurado exitosamente');
        }
        return redirect()->route('models.index')->with('error', 'Modelo no encontrado');
    }

    /**
     * Muestra la vista para asociar características a un modelo específico.
     *
     * Busca un modelo por su ID y obtiene todas las características disponibles.
     * Devuelve la vista de asociación con el modelo y las características listadas.
     *
     * @param int $id El ID del modelo al que se asociarán las características.
     * @return \Illuminate\View\View La vista para asociar características al modelo.
     */

    public function associateCharacteristics($id)
    {
        $model = Models::findOrFail($id); // Encontrar el modelo
        $caracteristicas = Characteristic::all(); // Listar todas las características

        return view('inventories.models.associate', compact('model', 'caracteristicas'));
    }

    /**
     * Almacena las características asociadas a un modelo en la base de datos.
     *
     * Valida que el request contenga un arreglo de características con sus respectivos valores.
     * Luego, busca el modelo por su ID y utiliza un bucle para asociar cada característica con su valor
     * a través de la relación `model_characteristics`. Si la característica ya existe, la actualiza.
     * Si se asocia con éxito, redirige a la vista de detalles del modelo con un mensaje de éxito.
     *
     * @param \Illuminate\Http\Request $request El request que contiene el arreglo de características.
     * @param int $id El ID del modelo al que se asociarán las características.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la vista de detalles del modelo.
     */
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

    /**
     * Adds a characteristic to a model.
     *
     * Validates the request to ensure a characteristic ID and value are provided and exist.
     * Checks if the characteristic with the given value already exists for the model, and if so,
     * returns a 422 error response. If not, it attaches the characteristic to the model with the specified value.
     * Returns a JSON response with the characteristic details for AJAX consumption.
     *
     * @param \Illuminate\Http\Request $request The incoming request with characteristic data.
     * @param int $id The ID of the model to which the characteristic will be added.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the added characteristic details.
     */

    public function addCharacteristic(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'characteristic_id' => 'required|exists:characteristics,id',
            'value' => 'required|string|max:255',
        ], [
            'characteristic_id.required' => 'La característica es obligatoria.',
            'characteristic_id.exists' => 'La característica no existe.',
            'value.required' => 'El valor es obligatorio.',
            'value.string' => 'El valor debe ser una cadena de texto.',
            'value.max' => 'El valor no debe superar los 255 caracteres.',
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

    /**
     * Elimina una característica de un modelo.
     *
     * Encuentra el modelo y la característica por sus IDs y elimina la relación
     * en la tabla pivote. Devuelve una respuesta JSON con el resultado de la
     * operación.
     *
     * @param int $modelId El ID del modelo del que se eliminará la característica.
     * @param int $characteristicId El ID de la característica que se eliminará.
     * @return \Illuminate\Http\JsonResponse
     */
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
