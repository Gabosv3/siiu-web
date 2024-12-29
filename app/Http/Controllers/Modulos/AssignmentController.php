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
use Illuminate\Http\Request;

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
            'user_id' => 'required|exists:users,id',
            'hardware_id' => 'required|exists:hardware,id',
        ], [
            'user_id.required' => 'El usuario es obligatorio.',
            'hardware_id.required' => 'El equipo es obligatorio.',
            'user_id.exists' => 'El usuario no existe.',
            'hardware_id.exists' => 'El equipo no existe.',
        ]);
    
        // Verifica si ya hay una asignación existente
        $existingAssignment = HardwareAssignment::where('hardware_id', $request->hardware_id)->first();
    
        if ($existingAssignment) {
            // Si existe, actualiza la asignación
            $existingAssignment->user_id = $request->user_id;
            $existingAssignment->save();
    
            // Actualiza el estado del hardware a "Reasignado"
            $hardware = Hardware::find($request->hardware_id);
            $hardware->status = 'Reasignado';
            $hardware->save();
    
            $user = User::find($request->user_id); // Obtener usuario
            EquipmentHistory::create([
                'hardware_id' => $request->hardware_id,
                'user_id' => $request->user_id,
                'action' => 'reasignación',
                'description' => 'Equipo reasignado a usuario.',
                'performed_at' => now(),
            ]);
        } else {
            // Si no existe, crea una nueva asignación
            HardwareAssignment::create([
                'hardware_id' => $request->hardware_id,
                'user_id' => $request->user_id,
            ]);
            
            // Actualiza el estado del hardware a "Asignado"
            $hardware = Hardware::find($request->hardware_id);
            $hardware->status = 'Asignado';
            $hardware->save();
    
            $user = User::find($request->user_id); // Obtener usuario
            EquipmentHistory::create([
                'hardware_id' => $request->hardware_id,
                'user_id' => $request->user_id,
                'action' => 'Asignación',
                'description' => 'Equipo asignado a usuario.',
                'performed_at' => now(),
            ]);
        }
    
        // Devuelve una respuesta JSON con la información actualizada
        return response()->json([
            'success' => true,
            'message' => 'Equipo asignado correctamente',
            'user_name' => $user->name, // Nombre del usuario
            'departament_name' => $user->departament->name ?? 'Sin departamento', // Nombre del departamento
            'status' => $hardware->status, // Estado del hardware
        ]);
    }
    
    
}
