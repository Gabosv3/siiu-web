<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\HardwareResource;
use App\Models\Hardware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HardwareController extends Controller
{
    public function index()
    {
        $hardware = Hardware::with('category')->get();  // Cargar la categoría relacionada
        return HardwareResource::collection($hardware);
    }

    public function show($id)
    {
        return new HardwareResource(Hardware::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'conflictos' => 'nullable|string',
            'estado' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'ubicacion_id' => 'required|exists:departamentos,id',
            'codigo_de_inventario' => 'required|string|unique:hardware,codigo_de_inventario',
            'numero_de_serie' => 'required|string|unique:hardware,numero_de_serie',
            'fabricante_id' => 'required|exists:fabricantes,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'sistemas_asignados' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hardware = Hardware::create($request->all());

        return new HardwareResource($hardware);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'conflictos' => 'nullable|string',
            'estado' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'ubicacion_id' => 'required|exists:departamentos,id',
            'codigo_de_inventario' => 'required|string|unique:hardware,codigo_de_inventario,' . $id,
            'numero_de_serie' => 'required|string|unique:hardware,numero_de_serie,' . $id,
            'fabricante_id' => 'required|exists:fabricantes,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'sistemas_asignados' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hardware = Hardware::findOrFail($id);
        $hardware->update($request->all());

        return new HardwareResource($hardware);
    }

    public function destroy($id)
    {
        Hardware::destroy($id);
        return response()->json(null, 204);
    }
}
