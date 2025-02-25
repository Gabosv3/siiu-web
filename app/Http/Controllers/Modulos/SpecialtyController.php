<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    
    /**
     * store a nuevo registro en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name',
        ]);

        // Crear la especialidad
        $specialty = Specialty::create([
            'name' => $request->name,
        ]);

        // Retornar la respuesta JSON
        return response()->json($specialty, 201);

        //comentario español
    }
}
