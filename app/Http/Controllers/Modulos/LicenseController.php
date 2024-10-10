<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Hardware;
use App\Models\License;
use App\Models\Software;
use Illuminate\Http\Request;


class LicenseController extends Controller
{
    //
    // Mostrar todas las licencias
    public function index(Request $request)
    {
        $licesesdeleted = License::onlyTrashed()->get();
        // Obtiene el software_id del query string si está presente
        $software_id = $request->input('software_id');

        // Obtiene todos los softwares para el dropdown
        $softwares = Software::all();

        if ($software_id) {
            // Obtiene el software correspondiente
            $software = Software::find($software_id);

            // Obtiene las licencias para el software específico
            $licenses = License::where('software_id', $software_id)->get();
        } else {
            // Si no hay software_id, obtiene todas las licencias
            $licenses = License::all();
            $software = null; // No hay software seleccionado
        }

        return view('inventories.licenses.index', compact('licenses', 'software', 'softwares', 'licesesdeleted'));
    }

    // Mostrar formulario para crear una nueva licencia
    public function create(Request $request)
    {
        $software_id = $request->input('software_id');

        // Obtiene el software correspondiente
        $software = Software::find($software_id);

        return view('inventories.licenses.create', compact('software'));
    }

    // Guardar una nueva licencia
    public function store(Request $request)
    {
        $request->validate([
            'software_id' => 'required|exists:softwares,id',
            'license_key.*' => 'required|string|unique:licenses,license_key',
            'purchase_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|string',
            'hardware_id' => 'nullable|exists:hardwares,id',
        ], [
            'software_id.exists' => 'El software no existe.',
            'license_key.unique' => 'La clave de licencia ya existe.',
            'purchase_date.date' => 'La fecha de compra no es una fecha válida.',
            'expiration_date.date' => 'La fecha de expiración no es una fecha válida.',
            'expiration_date.after_or_equal' => 'La fecha de expiración debe ser posterior o igual a la fecha de compra.',
            'status.required' => 'El estado es requerido.',
            'status.string' => 'El estado debe ser una cadena de texto.',

        ]);

        foreach ($request->license_key as $license_key) {
            License::create([
                'software_id' => $request->software_id,
                'license_key' => $license_key,
                'purchase_date' => $request->purchase_date,
                'expiration_date' => $request->expiration_date,
                'status' => $request->status,
                'hardware_id' => $request->hardware_id ?? null,
            ]);
        }

        return redirect()->route('licenses.index', ['software_id' => $request->software_id])
            ->with('success', 'Licencia creada exitosamente.');
    }

    // Mostrar una licencia específica
    public function show(License $license)
    {
        return view('inventories.licenses.show', compact('license'));
    }

    // Mostrar formulario para editar una licencia existente
    public function edit(License $license)
    {
        $softwares = Software::all();
        $equipos = Hardware::all();
        return view('inventories.licenses.edit', compact('license', 'softwares', 'equipos'));
    }

    // Actualizar una licencia existente
    public function update(Request $request, License $license)
    {
        $request->validate([
            'software_id' => 'required|exists:softwares,id',
            'license_key' => 'required|unique:licenses,license_key,' . $license->id,
            'expiration_date' => 'nullable|date',
            
        ], [
            'software_id.exists' => 'El software no existe.',
            'license_key.unique' => 'La clave de licencia ya existe.',
            'expiration_date.date' => 'La fecha de expiración no es una fecha válida.',
            'expiration_date.after_or_equal' => 'La fecha de expiración debe ser posterior o igual a la fecha de compra.',
        ]);

        $license->update($request->all());

        return redirect()->route('licenses.index')->with('success', 'Licencia actualizada exitosamente.');
    }

    // Eliminar una licencia
    public function destroy(License $license)
    {
        if ( $license->delete()) {
            return redirect()->route('licenses.index')->with('success', 'Licencia eliminada exitosamente.');
        }

        return redirect()->route('licenses.index')->with('error', 'Licencia no encontrada.');

        
    }

    public function restore($id)
    {
        $license = License::withTrashed()->find($id);
        if (!$license) {
            return redirect()->route('licenses.index')->with('error', 'Licencia no encontrada.');
        }
        $license->restore();
        return redirect()->route('licenses.index')->with('success', 'Licencia restaurada exitosamente.');
    }

    
}
