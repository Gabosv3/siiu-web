<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Characteristic;
use App\Models\Models;
use Illuminate\Http\Request;

class CharacteristicController extends Controller
{


    /**
     * Constructor del controlador.
     *
     * Establece middleware para controlar los permisos de acceso a los métodos
     * del controlador. Los middleware se aplican a los métodos según se indica
     * a continuación:
     *
     * - index: can:characteristic.index
     * - create y store: can:characteristic.create
     * - edit y update: can:characteristic.edit
     * - destroy: can:characteristic.destroy
     * - restore: can:characteristic.restore
     *
     */
    public function __construct()
    {
        $this->middleware('can:characteristic.index')->only('index');
        $this->middleware('can:characteristic.create')->only('store');
    }
    /**
     * Muestra una lista de todos los modelos con sus características.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Models::all();
        return view('inventories.models.index', compact('models'));
    }

    /**
     * Almacena una nueva característica en la base de datos.
     *
     * Valida los datos de entrada y crea una nueva instancia de la característica
     * utilizando los datos validados. Luego, devuelve la nueva característica en
     * formato JSON con un código de estado 201.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de la característica.
     * 
     * @return \Illuminate\Http\JsonResponse La respuesta JSON que incluye los datos de la nueva característica.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $characteristic = Characteristic::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return response()->json($characteristic, 201); // Devolver los datos de la nueva característica
    }
}
