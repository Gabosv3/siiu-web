<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Fabricante;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class FabricanteController extends Controller
{
    //
    // Método para crear un nuevo fabricante
    public function store(Request $request)
    {
        // Verifica que el método se esté llamando
        // dd('store method called'); 

        $request->validate([
            'nombre' => 'required|string|max:255',
            'type' => 'required|string|max:255',

        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.string' => 'El nombre debe ser una cadena de texto',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres',
            'type.required' => 'El tipo es obligatorio',
            'type.string' => 'El tipo debe ser una cadena de texto',
            'type.max' => 'El tipo no debe superar los 255 caracteres',
        ]);

        $fabricante = Manufacturer::create([
            'name' => $request->input('nombre'),
            'type' => $request->input('type'),
        ]);

        return response()->json([
            'id' => $fabricante->id,
            'nombre' => $fabricante->name,
            'type' => $fabricante->type,
        ]);
    }
}
