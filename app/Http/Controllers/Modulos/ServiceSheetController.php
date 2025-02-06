<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class ServiceSheetController extends Controller
{
    // Mostrar la vista de la hoja de servicio
    public function show($id)
    {


        return view('service-sheet.show');
    }

    // Guardar la hoja de servicio
    public function store(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        // Validar y guardar los datos de la hoja de servicio
        $validatedData = $request->validate([
            'diagnostic' => 'required|string',
            'description' => 'required|string',
            'consumed_items' => 'nullable|array', // Elementos consumidos
        ]);

        // Guardar la información en la base de datos
        $assignment->service_sheet = [
            'diagnostic' => $validatedData['diagnostic'],
            'description' => $validatedData['description'],
            'consumed_items' => $validatedData['consumed_items'],
        ];
        $assignment->save();

        return redirect()->route('service-sheet.show', $id)->with('success', 'Hoja de servicio guardada correctamente.');
    }
}
