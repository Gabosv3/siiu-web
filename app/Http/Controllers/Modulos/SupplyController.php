<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Models;
use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    /**
     * Constructor del controlador de insumos.
     *
     * Establece middleware para controlar los permisos de acceso a los
     * m todos del controlador. Los middleware se aplican a los m todos
     * seg n se indica a continuaci n:
     *
     * - index: can:supplies.index
     * - create y store: can:supplies.create
     * - edit y update: can:supplies.edit
     * - destroy: can:supplies.destroy
     * - restore: can:supplies.restore
     */
    /*
     public function __construct()
    {
        $this->middleware('can:supplies.index')->only('index');
        $this->middleware('can:supplies.create')->only('create', 'store');
        $this->middleware('can:supplies.edit')->only('edit', 'update');
        $this->middleware('can:supplies.destroy')->only('destroy');
        $this->middleware('can:supplies.restore')->only('restore');
    }
        */
    /**
     * Muestra la vista de listado de insumos.
     *
     * Obtiene todos los insumos y los insumos eliminados y los pasa a la vista
     * de listado de insumos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplies = Supply::with('category')->get();
        $deletedSupplies = Supply::onlyTrashed()->with('category')->get();
        return view('inventories.supplies.index', compact('supplies', 'deletedSupplies'));
    }

    
    /**
     * Muestra el formulario para crear un nuevo insumo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categoria = Category::find($request->input('category_id'));
        $fabricantes = Manufacturer::where('type', 'Insumo')->get();
        $modelos = Models::all();
        
        
        return view('inventories.supplies.create', [
            'categoria' => $categoria,
            'fabricantes' => $fabricantes,
            'modelos' => $modelos

        ]);
    }

    
    /**
     * Crea un nuevo insumo en la base de datos.
     *
     * Valida los datos del formulario y crea un nuevo insumo con los datos
     * proporcionados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ],
        [
            'name.required' => 'El nombre es obligatorio',
            'category_id.required' => 'La categoría es obligatoria',
            'quantity.required' => 'La cantidad es obligatoria',
            'unit.required' => 'El unidad es obligatorio',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser "active" o "inactive"',
            'category_id.exists' => 'La categoría no existe',
            'quantity.min' => 'La cantidad debe ser mayor o igual a 1',
            'unit.max' => 'La unidad no debe superar los 50 caracteres',
            'name.max' => 'El nombre no debe superar los 255 caracteres',
            'description.max' => 'La descripción no debe superar los 65535 caracteres',
            'status.in' => 'El estado debe ser "active" o "inactive"',
            'status.in' => 'El estado debe ser "active" o "inactive"',
            'category_id.exists' => 'La categoría no existe',
        ]);

        Supply::create($request->all());

        return redirect()->route('supplies.index')->with('success', 'Insumo creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un insumo existente.
     *
     * Obtiene la categoría, los fabricantes y los modelos asociados al insumo
     * y los pasa a la vista de edición de insumos.
     *
     * @param  \App\Models\Supply  $supply
     * @return \Illuminate\View\View
     */

    public function edit(Supply $supply)
    {
        $categoria = Category::find($supply->category_id);
        $fabricantes = Manufacturer::where('type', 'Insumo')->get();
        $modelos = Models::all();
        $insumo  = $supply;
        return view('inventories.supplies.edit', compact( 'categoria','insumo', 'fabricantes', 'modelos'));
    }

    /**
     * Actualiza un insumo existente en la base de datos.
     *
     * Valida los datos del formulario y actualiza el insumo con los datos
     * proporcionados. Redirige a la vista de listado de insumos con un mensaje
     * de éxito si la operación es exitosa.
     *
     * @param  \Illuminate\Http\Request  $request  La solicitud HTTP que contiene los datos del formulario.
     * @param  \App\Models\Supply  $supply  La instancia del modelo Supply que se va a actualizar.
     * @return \Illuminate\Http\RedirectResponse  La respuesta de redirección a la ruta 'supplies.index'.
     */

    public function update(Request $request, Supply $supply)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ],
        [
            'name.required' => 'El nombre es obligatorio',
            'category_id.required' => 'La categoría es obligatoria',
            'quantity.required' => 'La cantidad es obligatoria',
            'unit.required' => 'El unidad es obligatorio',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser "active" o "inactive"',
            'category_id.exists' => 'La categoría no existe',
            'quantity.min' => 'La cantidad debe ser mayor o igual a 1',
            'unit.max' => 'La unidad no debe superar los 50 caracteres',
            'name.max' => 'El nombre no debe superar los 255 caracteres',
            'description.max' => 'La descripción no debe superar los 65535 caracteres',
            'category_id.exists' => 'La categoría no existe',
        ]);

        $supply->update($request->all());

        return redirect()->route('supplies.index')->with('success', 'Insumo actualizado exitosamente.');
    }

    /**
     * Muestra la vista de detalles de un insumo específico.
     *
     * Obtiene el insumo por su ID y lo pasa a la vista 'supplies.show' para mostrar
     * sus detalles.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supply = Supply::findOrFail($id);
        return view('inventories.supplies.show', compact('supply'));
    }

    
    /**
     * Elimina un insumo existente de la base de datos.
     *
     * Busca el insumo por su ID y lo elimina de la base de datos.
     * Redirige a la vista de listado de insumos con un mensaje de éxito
     * si la operación es exitosa.
     *
     * @param  int  $id  El ID del insumo que se va a eliminar.
     * @return \Illuminate\Http\RedirectResponse  La respuesta de redirección a la ruta 'supplies.index'.
     */
    public function destroy($id)
    {
        $supply = Supply::findOrFail($id);
        $supply->delete();

        return redirect()->route('supplies.index')->with('success', 'Insumo eliminado exitosamente.');
    }

    /**
     * Restaura un insumo eliminado de la base de datos.
     *
     * Busca el insumo eliminado por su ID y lo restaura. Luego redirige
     * a la lista de insumos con un mensaje de éxito.
     *
     * @param int $id El ID del insumo a restaurar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección con el resultado de la operación.
     */

    public function restore($id)
    {
        $supply = Supply::withTrashed()->findOrFail($id);
        $supply->restore();

        return redirect()->route('supplies.index')->with('success', 'Insumo restaurado exitosamente.');
    }
}
