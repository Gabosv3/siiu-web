<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Software;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    /**
     * Mostrar una lista de todos los software.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $software = Software::all();
        return view('inventario.software.index', compact('software'));
    }

    /**
     * Mostrar el formulario para crear un nuevo software.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('inventario.software.create');
    }

    /**
     * Almacenar un nuevo software en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'nombre_software' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'fabricante' => 'required|string|max:255',
            'asignada' => 'nullable|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'clasificacion_licencia' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'clave_licencia' => 'nullable|string|max:255',
            'fecha_compra' => 'nullable|date',
        ]);

        Software::create($request->all());

        return redirect()->route('softwares.index')
                         ->with('success', 'Software creado exitosamente.');
    }

    /**
     * Mostrar un software específico.
     *
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function show(Software $software)
    {
        return view('inventario.software.show', compact('software'));
    }

    /**
     * Mostrar el formulario para editar un software existente.
     *
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function edit(Software $software)
    {
        return view('inventario.software.edit', compact('software'));
    }

    /**
     * Actualizar un software existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Software $software)
    {
        $request->validate([
            'nombre_software' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'fabricante' => 'required|string|max:255',
            'asignada' => 'nullable|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'clasificacion_licencia' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'clave_licencia' => 'nullable|string|max:255',
            'fecha_compra' => 'nullable|date',
        ]);

        $software->update($request->all());

        return redirect()->route('softwares.index')
                         ->with('success', 'Software actualizado exitosamente.');
    }

    /**
     * Eliminar un software de la base de datos.
     *
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function destroy(Software $software)
    {
        $software->delete();

        return redirect()->route('softwares.index')
                         ->with('success', 'Software eliminado exitosamente.');
    }
}