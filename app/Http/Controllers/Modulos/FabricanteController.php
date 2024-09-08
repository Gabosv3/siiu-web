<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Fabricante;
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
        ]);

        $fabricante = Fabricante::create([
            'nombre' => $request->input('nombre'),
        ]);

        return response()->json([
            'id' => $fabricante->id,
            'nombre' => $fabricante->nombre,
        ]);
    }
}
