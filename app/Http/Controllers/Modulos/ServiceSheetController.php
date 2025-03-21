<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\EquipmentHistory;
use App\Models\Hardware;
use App\Models\ServiceSheet;
use App\Models\Supply;
use App\Models\Ticket;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceSheetController extends Controller
{

    /**
     * Muestra la vista para crear una hoja de servicios
     *
     * @param int $id Identificador del registro de la asignaci n
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $CategoryListhardware = Category::where('type', 'Equipo')->get();
        $CategoryListInsumo = Category::where('type', 'Insumo')->get();
        $data = Assignment::with(['technician.user', 'ticket'])->find($id);
        $supplies = Supply::all();
        return view('service_sheets.create', compact('CategoryListhardware', 'CategoryListInsumo', 'data', 'supplies'));
    }

    /**
     * Almacena nuevas hojas de servicio en la base de datos.
     *
     * Valida los datos de la solicitud antes de crear una o más hojas de servicio.
     * Cada hoja de servicio está asociada a un hardware, un ticket y puede incluir
     * insumos. Se adjuntan insumos a la hoja de servicio si se proporcionan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function store(Request $request)
     {
         $validated = $request->validate([
             'date' => 'nullable|date',
             'department_id' => 'required|exists:departaments,id',
             'user_id' => 'required|exists:users,id',
             'technician_id' => 'required|exists:users,id',
             'ticket_id' => 'required|exists:tickets,id',
             'hardware_id' => 'nullable|exists:hardware,id',
             'supplies_data' => 'nullable|json',
             'description' => 'required|string',
             'observations' => 'nullable|string',
         ], [
             'supplies_data.json' => 'Los insumos deben ser un formato JSON válido.',
             'department_id.exists' => 'El departamento seleccionado no existe.',
             'user_id.exists' => 'El usuario seleccionado no existe.',
             'technician_id.exists' => 'El técnico seleccionado no existe.',
             'ticket_id.exists' => 'El ticket seleccionado no existe.',
             'hardware_id.exists' => 'El equipo seleccionado no existe.',
             'description.required' => 'La descripción es obligatoria.',
         ]);

         DB::beginTransaction();
         try {
             $serviceSheet = ServiceSheet::create([
                 'date' => now(),
                 'department_id' => $validated['department_id'],
                 'user_id' => $validated['user_id'],
                 'technician_id' => $validated['technician_id'],
                 'ticket_id' => $validated['ticket_id'],
                 'hardware_id' => $validated['hardware_id'] ?? null,
                 'supplies_data' => json_decode($validated['supplies_data'], true),
                 'description' => $validated['description'],
                 'observations' => $validated['observations'] ?? null,
             ]);

             // Reducir los insumos
             if (!empty($serviceSheet->supplies_data)) {
                 foreach ($serviceSheet->supplies_data as $supply) {
                     $item = Supply::findOrFail($supply['supply_id']);
                     $quantity = (int) $supply['quantity'];

                     if ($item->quantity < $quantity) {
                         throw new Exception("No hay suficientes insumos para el ID {$supply['supply_id']}.");
                     }

                     $item->decrement('quantity', $quantity);
                 }
             }

             // Crear historial de equipo si existe un hardware asociado
             if ($serviceSheet->hardware_id) {
                 EquipmentHistory::create([
                     'hardware_id' => $serviceSheet->hardware_id,
                     'user_id' => $serviceSheet->user_id,
                     'action' => 'Asociación a hoja de servicio',
                     'description' => "Equipo asociado a la hoja de servicio ID: {$serviceSheet->id}.",
                     'performed_at' => now(),
                 ]);
             }

             // Actualizar el estado del ticket
             Ticket::where('id', $serviceSheet->ticket_id)->update(['status' => 'resuelto']);

             DB::commit();

             // Generar PDF
             $pdf = PDF::loadView('service_sheets.pdf', compact('serviceSheet'));
             return $pdf->download("hoja_de_servicio_{$serviceSheet->id}.pdf");
         } catch (Exception $e) {
             DB::rollBack();
             return back()->withErrors(['error' => $e->getMessage()]);
         }
     }


    /**
     * Muestra una hoja de servicio en detalle.
     *
     * @param ServiceSheet $serviceSheet La hoja de servicio a mostrar.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceSheet $serviceSheet)
    {
        return view('service_sheets.show', compact('serviceSheet'));
    }


    /**
     * Obtiene una lista de hardware que pertenecen a una categoría específica.
     *
     * @param int $categoryId La ID de la categoría a la que pertenece el hardware.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHardware($categoryId)
    {
        $hardware = Hardware::where('category_id', $categoryId)->get();
        return response()->json($hardware);
    }


    /**
     * Obtiene una lista de insumos que pertenecen a una categoría específica.
     *
     * @param int $categoryId La ID de la categoría a la que pertenecen los insumos.
     *
     * @return \Illuminate\Http\Response La lista de insumos en formato JSON.
     */
    public function getSupplies($categoryId)
    {
        $supplies = Supply::where('category_id', $categoryId)->get();
        //comentario español
        return response()->json($supplies);
    }

    /**
     * Obtiene los detalles de un hardware por su ID.
     *
     * @param int $id La ID del hardware a obtener.
     *
     * @return \Illuminate\Http\Response Un objeto JSON con los detalles del hardware.
     */

    public function getHardwareDetails($id)
    {
        $hardware = Hardware::select(
            'model_id',
            'inventory_code',
            'serial_number',
            'warranty_expiration_date',
            'barcode_path',
            'status'
        )->with('model:id,name')->find($id);

        if (!$hardware) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }

        return response()->json([
            'model_name' => $hardware->model->name ?? 'Sin modelo',
            'inventory_code' => $hardware->inventory_code,
            'serial_number' => $hardware->serial_number,
            'warranty_expiration_date' => $hardware->warranty_expiration_date,
            'barcode_path' => asset("storage/" . $hardware->barcode_path),
            'status' => $hardware->status
        ]);
    }
}
