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
use Carbon\Carbon;
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

        $request->validate([
            'date' => 'nullable|date',
            'department_id' => 'required|exists:departaments,id',
            'user_id' => 'required|exists:users,id',
            'technician_id' => 'required|exists:users,id',
            'ticket_id' => 'required|exists:tickets,id',
            'hardware_id' => 'nullable|exists:hardware,id',
            'supplies_data' => 'nullable|json',  // Asegura que supplies_data sea un JSON válido
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

        $serviceSheet = new ServiceSheet();
        $serviceSheet->date = $request->date;
        $serviceSheet->department_id = $request->department_id;
        $serviceSheet->user_id = $request->user_id;
        $serviceSheet->technician_id = $request->technician_id;
        $serviceSheet->ticket_id = $request->ticket_id;
        $serviceSheet->hardware_id = $request->hardware_id;
        $serviceSheet->supplies_data = json_decode($request->supplies_data, true); // Decodificar JSON a array
        $serviceSheet->description = $request->description;
        $serviceSheet->observations = $request->observations;
        
        if ($serviceSheet->save()) {
            // Obtener el nombre del usuario si existe
            $userName = optional($serviceSheet->user)->name ?? 'Desconocido';
        
            EquipmentHistory::create([
                'hardware_id' => $serviceSheet->hardware_id,
                'user_id' => $serviceSheet->user_id,
                'action' => 'Asociación a hoja de servicio',
                'description' => "Equipo asociado a la hoja de servicio ID: {$serviceSheet->id} para el usuario: {$userName}.",
                'performed_at' => now(),
            ]);
            $ticket = Ticket::findOrFail($serviceSheet->ticket_id);
            $ticket->update([
                'status' => 'resuelto', // Cambiar el estado a "resuelto"
            ]);

        }

        $id = $serviceSheet->ticket_id;
        return redirect()->route('service-sheet.create', ['id' => $id])->with('success', 'Hoja de servicio creada exitosamente.');
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
