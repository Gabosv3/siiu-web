<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Departament;
use App\Models\EquipmentHistory;
use App\Models\Hardware;
use App\Models\HardwareAssignment;
use App\Models\Specialty;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
    public function index(Request $request)
    {
        $technicianId = Technician::where('user_id', Auth::id())->value('id');

        // Obtener solo las asignaciones del técnico autenticado
        $assignments = Assignment::with(['technician.user', 'ticket'])
            ->join('tickets', 'assignments.ticket_id', '=', 'tickets.id')
            ->where('assignments.technician_id', $technicianId)
            ->where('tickets.status', 'en proceso')
            ->orderByRaw("FIELD(tickets.priority, 'alta', 'media', 'baja')")
            ->orderBy('tickets.created_at', 'asc')
            ->select('assignments.*', 'tickets.priority', 'tickets.created_at', 'tickets.status')
            ->get();

        $statusFilter = $request->input('status', 'en proceso');

        if ($assignments->isEmpty()) {
            $events = "No hay asignaciones disponibles.";

            // **Crear una paginación vacía** para evitar el error con links()
            $tableAssignments = new LengthAwarePaginator([], 0, 5);

            return view('components.calendar', compact('events', 'statusFilter', 'tableAssignments'));
        }

        // Mapear los resultados para el calendario
        $events = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'title' => $assignment->technician->user->name . ' - ' . $assignment->task,
                'start' => $assignment->initial_date,
            ];
        });

        // Obtener las asignaciones con filtro por estado
        $tableAssignments = Assignment::with(['technician.user', 'ticket'])
            ->join('tickets', 'assignments.ticket_id', '=', 'tickets.id')
            ->where('assignments.technician_id', $technicianId)
            ->where('tickets.status', $statusFilter)
            ->orderByRaw("FIELD(tickets.priority, 'alta', 'media', 'baja')")
            ->orderBy('tickets.created_at', 'asc')
            ->select('assignments.*', 'tickets.priority', 'tickets.created_at', 'tickets.status')
            ->paginate(5); // Esto asegura que SIEMPRE sea paginado

        return view('components.calendar', compact('events', 'tableAssignments', 'statusFilter'));
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
            'user_id' => 'nullable|exists:users,id', // Aceptar solo un user_id
            'departament_id' => 'nullable|exists:departaments,id',
            'hardware_id' => 'required|exists:hardware,id',
        ], [
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'departament_id.exists' => 'El departamento no existe.',
            'hardware_id.exists' => 'El equipo no existe.',
        ]);

        // Buscar el hardware
        $hardware = Hardware::findOrFail($request->hardware_id);

        // Solo procesar si alguno de los dos campos (user_id o departament_id) está presente
        if ($request->user_id != null || $request->departament_id != null) {

            // Usar solo los campos proporcionados en la solicitud para actualizar el hardware assignment
            $updateData = [];

            // Asignar el user_id si está presente
            if ($request->filled('user_id')) {
                $updateData['user_id'] = $request->user_id;
            }

            // Asignar el departament_id si está presente
            if ($request->filled('departament_id')) {
                $updateData['departament_id'] = $request->departament_id;
            }

            // Actualizar o crear la asignación de hardware
            HardwareAssignment::updateOrCreate(
                [
                    'hardware_id' => $hardware->id,
                ],
                $updateData // Solo actualizamos con los campos presentes en la solicitud
            );

            // Registrar en el historial si se ha asignado a un usuario
            if ($request->filled('user_id')) {
                $userName = User::find($request->user_id)->name;

                EquipmentHistory::create([
                    'hardware_id' => $hardware->id,
                    'user_id' => $request->user_id,
                    'action' => 'Asignación a usuario',
                    'description' => "Equipo asignado al usuario: {$userName}.",
                    'performed_at' => now(),
                ]);
            }

            // Registrar en el historial si se ha asignado a un departamento
            if ($request->filled('departament_id')) {
                $departmentName = Departament::find($request->departament_id)->name;

                EquipmentHistory::create([
                    'hardware_id' => $hardware->id,
                    'user_id' => $request->filled('user_id') ? $request->user_id : null,
                    'action' => 'Asignación a departamento',
                    'description' => "Equipo asignado al departamento: {$departmentName}.",
                    'performed_at' => now(),
                ]);
            }
        }

        // Actualizar el estado del hardware
        $hardware->update(['status' => 'Asignado']);

        return response()->json([
            'success' => true,
            'message' => 'Equipo asignado correctamente.',
            'status' => $hardware->status,
            'assigned_to' => $hardware->users()->pluck('name')->join(', ') ?: 'Departamento',
            'department_name' => $hardware->hardwareAssignments->first()->departament->name ?? null,
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
