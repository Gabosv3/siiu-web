<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\EquipmentHistory;
use App\Models\Hardware;
use App\Models\HardwareAssignment;
use App\Models\Specialty;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    //

    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar métodos específicos
        $this->middleware('can:asignar.index')->only('index', 'showAssignForm');
        $this->middleware('can:asignar.create')->only('assign');
        $this->middleware('can:asignar.edit')->only('edit', 'update');
        $this->middleware('can:asignar.destroy')->only('destroy');
    }

    public function index()
    {
        return view('components.calendar');
    }

    // Mostrar el formulario de asignación de ticket
    public function showAssignForm($id)
    {
        $ticket = Ticket::findOrFail($id);
        $technicians = Technician::all();
        $specialties = Specialty::all();

        return view('tickets.assign', compact('ticket', 'technicians', 'specialties'));
    }

    // Asignar un ticket
    public function assign(Request $request, $id)
    {
        $request->validate(
            [
                'priority' => 'required|string|in:alta,media,baja',
                'specialty_id' => 'required|exists:specialties,id',
                'technician_id' => 'required|exists:technicians,id',
                'task' => 'required|string',
                'initial_date' => 'required|date|after_or_equal:today',
            ],
            [
                'priority.required' => 'El campo prioridad es obligatorio.',
                'specialty_id.required' => 'El campo especialidad es obligatorio.',
                'technician_id.required' => 'El campo técnico es obligatorio.',
                'task.required' => 'El campo tarea es obligatorio.',
                'initial_date.required' => 'El campo fecha inicial es obligatorio.',
                'initial_date.after_or_equal' => 'La fecha inicial debe ser posterior o igual a la fecha actual.',
            ]
        );

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'priority' => $request->input('priority'),
            'technician_id' => $request->input('technician_id'),
            'status' => 'en proceso', // Asegúrate de usar el valor correcto
        ]);

        Assignment::create([
            'technician_id' => $request->input('technician_id'),
            'ticket_id' => $ticket->id,
            'task' => $request->input('task'),
            'status' => 'pendiente',
            'initial_date' => $request->input('initial_date'),
        ]);

        return redirect()->route('tickets.assignForm', $id)->with('success', 'Ticket asignado exitosamente.');
    }
    public function assignEquipment(Request $request)
{
    // Validar los datos de la solicitud
    $request->validate([
        'user_ids' => 'nullable|array',
        'user_ids.*' => 'exists:users,id',
        'departament_id' => 'nullable|exists:departaments,id',
        'hardware_id' => 'required|exists:hardware,id',
    ], [
        'user_ids.*.exists' => 'Alguno de los usuarios seleccionados no existe.',
        'departament_id.exists' => 'El departamento no existe.',
        'hardware_id.exists' => 'El equipo no existe.',
    ]);

    // Buscar el hardware
    $hardware = Hardware::find($request->hardware_id);
    if (!$hardware) {
        return response()->json([
            'success' => false,
            'message' => 'El equipo solicitado no existe.',
        ], 404);
    }

    // Verificar si el equipo ya está asignado a algún usuario
    if ($request->filled('user_ids')) {
        foreach ($request->user_ids as $userId) {
            // Verificar si ya está asignado el equipo a ese usuario
            if ($hardware->users()->where('user_id', $userId)->exists()) {
                continue; // Si ya está asignado, continuar con el siguiente usuario
            }

            // Asignar el equipo al usuario si no está asignado
            $hardware->users()->attach($userId);

            // Registrar en el historial
            EquipmentHistory::create([
                'hardware_id' => $hardware->id,
                'user_id' => $userId,
                'action' => 'Asignación a usuario',
                'description' => "Equipo asignado al usuario ID: {$userId}.",
                'performed_at' => now(),
            ]);
        }
    }

    // Asignación a departamento
    if ($request->filled('departament_id')) {
        // Solo asignar el departamento si no existe un usuario asignado
        HardwareAssignment::create([
            'hardware_id' => $hardware->id,
            'user_id' => null, // Asignamos null a user_id ya que no se asigna un usuario
            'departament_id' => $request->departament_id,
        ]);

        // Registrar en el historial
        EquipmentHistory::create([
            'hardware_id' => $hardware->id,
            'user_id' => null,
            'action' => 'Asignación a departamento',
            'description' => "Equipo asignado al departamento ID: {$request->departament_id}.",
            'performed_at' => now(),
        ]);
    }

    // Actualizar el estado del hardware
    $hardware->status = 'Asignado';
    $hardware->save();

    return response()->json([
        'success' => true,
        'message' => 'Equipo asignado correctamente.',
        'status' => $hardware->status,
        'assigned_to' => $hardware->users()->pluck('name')->join(', '),
        'department_name' => $hardware->hardwareAssignments->first()->department->name ?? null,
    ]);
}




}
