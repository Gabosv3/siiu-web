<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\Software;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    public function index()
    {
        $softwaresdeleted = Software::onlyTrashed()->get();
        // Obtiene todas las licencias
        $softwares = Software::with(['manufacturer', 'licencias'])->paginate(10);
        return view('inventories.softwares.index', compact('softwares', 'softwaresdeleted'));
    }

    public function create()
    {
        $fabricantes = Manufacturer::all();
        return view('inventories.softwares.create', compact('fabricantes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'manufacturer_id' => 'required|exists:manufacturers,id',
                'software_name' => 'required|string|max:255',
                'version' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            [
                'manufacturer_id.required' => 'El fabricante es obligatorio',
                'software_name.required' => 'El nombre del software es obligatorio',
                'version.required' => 'La versión es obligatoria',
            ]
        );

        Software::create($validatedData);

        return redirect()->route('softwares.index')->with('success', 'Software creado exitosamente');
    }

    public function edit(Software $software)
    {
        $fabricantes = Manufacturer::all();
        return view('inventories.softwares.edit', compact('software', 'fabricantes'));
    }

    public function update(Request $request, Software $software)
    {
        $validatedData = $request->validate(
            [
                'manufacturer_id' => 'required|exists:manufacturers,id',
                'software_name' => 'required|string|max:255',
                'version' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            [
                'manufacturer_id.required' => 'El fabricante es obligatorio',
                'software_name.required' => 'El nombre del software es obligatorio',
                'version.required' => 'La versión es obligatoria',
            ]
        );

        $software->update($validatedData);

        // Registro en el log


        return redirect()->route('softwares.index')->with('success', 'Software actualizado exitosamente');
    }

    public function show($id)
    {
        // Encontrar el software por su ID
        $software = Software::findOrFail($id);

        // Obtener las licencias vinculadas a este software
        $licencias = $software->licencias;

        // Contar cuántas licencias tiene este software
        $totalLicencias = $licencias->count();

        // Retornar la vista con el software, las licencias y el total de licencias
        return view('inventories.softwares.show', compact('software', 'licencias', 'totalLicencias'));
    }

    public function destroy(Software $software)
    {
        $software->delete();

        // Registro en el log
        if ($software) {
            return redirect()->route('softwares.index')->with('success', 'Software eliminado exitosamente');
        }

        return redirect()->route('softwares.index')->with('error', 'Software no encontrado');
    }

    public function restore($id)
    {
        // Buscar el software eliminado por su ID
        $software = Software::withTrashed()->find($id);

        if ($software) {
            // Restaurar el software eliminado
            $software->restore();
            return redirect()->back()->with('success', 'Software restaurado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'Software no encontrado.');
        }
    }
}
