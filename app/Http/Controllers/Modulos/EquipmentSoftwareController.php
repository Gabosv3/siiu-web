<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\EquipmentSoftware;
use App\Models\License;
use Illuminate\Http\Request;

class EquipmentSoftwareController extends Controller
{
     // Asignar software y licencia a un equipo
     public function assignSoftware(Request $request)
     {
         // Validar datos recibidos
         $validated = $request->validate([
             'hardware_id' => 'required|exists:hardware,id',
             'software_id' => 'required|exists:softwares,id',
             'license_id'  => 'nullable|exists:licenses,id',
         ]);

         // Verificar si la licencia es válida
         if (!empty($validated['license_id'])) {
             $license = License::find($validated['license_id']);

             // Validar que no esté expirada
             if ($license->expiration_date && $license->expiration_date < now()) {
                 return response()->json([
                     'message' => 'La licencia seleccionada ha expirado.',
                 ], 400);
             }

             // Validar el número máximo de dispositivos permitidos
             $activeDevices = EquipmentSoftware::where('license_id', $license->id)->count();
             if ($activeDevices >= $license->max_devices) {
                 return response()->json([
                     'message' => 'La licencia ya alcanzó el máximo de dispositivos permitidos.',
                 ], 400);
             }
         }

         // Crear la relación en la tabla intermedia
         $assignment = EquipmentSoftware::create([
             'hardware_id' => $validated['hardware_id'],
             'software_id' => $validated['software_id'],
             'license_id'  => $validated['license_id'] ?? null,
         ]);

         return response()->json([
             'message' => 'Software y licencia asignados correctamente.',
             'data'    => $assignment,
         ], 201);
     }
}
