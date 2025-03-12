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
use App\Notifications\TicketAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{

    /**
     * Constructor para aplicar middleware a ciertos métodos
     *
     * En este constructor se definen los middleware para verificar permisos
     * antes de ejecutar los métodos específicos de esta clase.
     *
     * @return void
     */

    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar métodos específicos
        $this->middleware('can:asignar.index')->only('index', 'showAssignForm');
        $this->middleware('can:asignar.create')->only('assign');
        $this->middleware('can:asignar.edit')->only('edit', 'update');
        $this->middleware('can:asignar.destroy')->only('destroy');
    }

    /**
     * Muestra el calendario con las asignaciones del técnico autenticado
     *
     * Esta función muestra un calendario con las asignaciones del técnico
     * autenticado. Se ordenan por prioridad y luego por fecha de creación
     * del ticket.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $technicianId = Technician::where('user_id', Auth::id())->value('id');

        // Obtener solo las asignaciones del técnico autenticado con el nombre del técnico y la tarea
        $assignments = Assignment::with(['technician.user', 'ticket']) // Cargar las relaciones
            ->join('tickets', 'assignments.ticket_id', '=', 'tickets.id') // Unir con la tabla tickets
            ->where('assignments.technician_id', $technicianId) // Especificar la tabla assignments para technician_id
            ->orderByRaw("FIELD(tickets.priority, 'alta', 'media', 'baja')") // Ordenar por prioridad del ticket
            ->orderBy('tickets.created_at', 'asc') // Ordenar por la fecha de creación del ticket
            ->select('assignments.*', 'tickets.priority', 'tickets.created_at') // Seleccionar los campos necesarios
            ->get();

        // Comprobar si hay asignaciones
        if ($assignments->isEmpty()) {
            $events = "No hay asignaciones disponibles.";
            return view('components.calendar', compact('events'));
        }

        // Mapear los resultados
        $events = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id, // Asegúrate de incluir el ID
                'title' => $assignment->technician->user->name . ' - ' . $assignment->task,
                'start' => $assignment->initial_date, // Usar 'initial_date' desde assignments
            ];
        });

        return view('components.calendar', compact('events'));
    }

    /**
     * Muestra el formulario para asignar un técnico a un ticket específico.
     *
     * Este método recupera la información del ticket, así como todos los técnicos
     * y especialidades disponibles, y los pasa a la vista de asignación.
     *
     * @param int $id El ID del ticket que se va a asignar.
     * @return \Illuminate\View\View La vista de asignación de tickets.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el ticket no se encuentra.
     */



    public function showAssignForm($id)
    {
        $ticket = Ticket::findOrFail($id);
        $technicians = Technician::all();
        $specialties = Specialty::all();

        return view('tickets.assign', compact('ticket', 'technicians', 'specialties'));
    }

    /**
     * Asigna un técnico a un ticket y crea una asignación con los datos proporcionados.
     *
     * Esta función actualiza el ticket con la prioridad, técnico y estado proporcionados,
     * y crea una asignación con la tarea, fecha inicial y estado pendiente.
     *
     * @param \Illuminate\Http\Request $request La petición HTTP con los datos del formulario.
     * @param int $id El ID del ticket que se va a asignar.
     *
     * @return \Illuminate\Http\RedirectResponse Una redirección a la ruta de asignación con un mensaje de éxito.

     */
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
            'technician_id.required' => 'El campo técnico es obligatorio.',
            'task.required' => 'El campo tarea es obligatorio.',
            'initial_date.required' => 'El campo fecha inicial es obligatorio.',
            'initial_date.after_or_equal' => 'La fecha inicial debe ser posterior o igual a la fecha actual.',
        ]
    );

    $ticket = Ticket::findOrFail($id);
    $technician = Technician::findOrFail($request->input('technician_id')); // Obtener el técnico asignado

    $ticket->update([
        'priority' => $request->input('priority'),
        'technician_id' => $technician->id,
        'status' => 'en proceso',
    ]);

    Assignment::create([
        'technician_id' => $technician->id,
        'ticket_id' => $ticket->id,
        'task' => $request->input('task'),
        'status' => 'pendiente',
        'initial_date' => $request->input('initial_date'),
    ]);

    // 📌 Notificar al usuario que creó el ticket
    if ($ticket->userticket) { // Verifica que el ticket tenga un usuario asignado
        $ticket->userticket->notify(new TicketAssignedNotification($ticket, $technician));
    }

    return redirect()->route('tickets.assignForm', $id)->with('success', 'Ticket asignado exitosamente.');
}

    /**
     * Asigna un equipo a uno o varios usuarios y a un departamento.
     *
     * Recibe un array de IDs de usuarios y un ID de departamento.
     * Si no se proporcionan usuarios, se asigna solo al departamento.
     * Si no se proporciona un departamento, se asigna solo a los usuarios.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        $hardware = Hardware::findOrFail($request->hardware_id);

        // Verificar y asignar a usuarios
        if ($request->filled('user_ids')) {
            foreach ($request->user_ids as $userId) {
                // Asignar solo si no está ya asignado
                $hardware->users()->syncWithoutDetaching([$userId]);

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

        // Verificar y asignar a un departamento
        if ($request->filled('departament_id')) {
            // Si no hay un usuario asignado, asignamos 'user_id' como null
            $userId = $hardware->users()->exists() ? $hardware->users->first()->id : null;

            HardwareAssignment::updateOrCreate(
                [
                    'hardware_id' => $hardware->id,
                    'departament_id' => $request->departament_id,
                ],
                [
                    'user_id' => $userId, // Asignamos 'user_id' según esté vacío o no
                    'departament_id' => $request->departament_id,
                ]
            );

            // Registrar en el historial
            EquipmentHistory::create([
                'hardware_id' => $hardware->id,
                'user_id' => $userId,
                'action' => 'Asignación a departamento',
                'description' => "Equipo asignado al departamento ID: {$request->departament_id}.",
                'performed_at' => now(),
            ]);
        }


        // Actualizar el estado del hardware
        $hardware->update(['status' => 'Asignado']);

        return response()->json([
            'success' => true,
            'message' => 'Equipo asignado correctamente.',
            'status' => $hardware->status,
            'assigned_to' => $hardware->users()->pluck('name')->join(', ') ?: 'Departamento',
            'department_name' => $hardware->hardwareAssignments->first()->department->name ?? null,
        ]);
    }
    
    /**
     * Obtiene los técnicos asignados a una especialidad dada.
     *
     * @param int $specialty_id ID de la especialidad
     *
     */
    /******  829510ce-d520-4311-b541-c68341a45de0  *******/
    public function getTechniciansBySpecialty($specialty_id)
    {
        try {
            // Obtener técnicos con su relación de usuario
            $technicians = Technician::where('specialty_id', $specialty_id)->with('user')->get();

            return response()->json($technicians);
        } catch (\Exception $e) {
            // Registrar el error y devolver una respuesta JSON con el mensaje de error

            return response()->json(['error' => 'Ocurrió un error al obtener los técnicos.'], 500);
        }
    }
}
