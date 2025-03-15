<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Ticket;
use App\Models\Title;
use App\Models\User;
use App\Notifications\TicketNotification;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:tickets.index')->only('index');
        $this->middleware('can:tickets.create')->only('createTicket');
        $this->middleware('can:tickets.show')->only('show');
        $this->middleware('can:tickets.edit')->only('edit', 'update');
        $this->middleware('can:tickets.destroy')->only('destroy');
    }

    /**
     * Muestra una lista de los tickets que ha creado el usuario autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = $tickets = Ticket::paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Muestra el formulario para crear un nuevo ticket.
     *
     * @return \Illuminate\View\View La vista para crear un ticket con los títulos disponibles.
     */

    public function crearTicketindex()
    {
        $titles = Title::all();
        return view('tickets.users.create', compact('titles'));
    }
    /**
     * Crea un nuevo ticket con la información proporcionada por el usuario
     * autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTicket(Request $request)
    {
        $request->validate([
            'title_id' => 'required|exists:titles,id',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => auth()->id(), // El usuario actual que crea el ticket
            'title_id' => $request->input('title_id'),
            'description' => $request->input('description'),
            'status' => 'abierto', // El ticket se crea con estado 'abierto'
        ]);

        // Obtener el usuario que creó el ticket
        $user = User::find(auth()->id()); // Obtén el usuario actual

        // Enviar la notificación al usuario que creó el ticket
        $user->notify(new TicketNotification($ticket));

        return redirect()->back()->with('success', 'Ticket creado exitosamente.');
    }

    /**
     * Muestra una lista de los tickets que ha creado el usuario autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function Mytickets(Request $request)
    {
        // Obtener los filtros de la solicitud
        $status = $request->get('status', ''); // Filtro de estado: 'abierto' por defecto
        $search = $request->get('search'); // Filtro de búsqueda
        $perPage = $request->get('perPage', 10); // Número de registros por página (10 por defecto)

        // Construcción de la consulta
        $ticketsQuery = Ticket::where('user_id', auth()->id()) // Filtro por el ID del usuario autenticado
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%');
                });
            })
            ->when($status === 'cerrado', function ($query) {
                return $query->where('status', 'cerrado'); // Filtro por tickets cerrados
            })
            ->when($status === 'resuelto', function ($query) {
                return $query->where('status', 'resuelto'); // Filtro por tickets resueltos
            })
            ->when($status === 'en proceso', function ($query) {
                return $query->where('status', 'en proceso'); // Filtro por tickets en proceso
            })
            ->when($status === 'abierto', function ($query) {
                return $query->where('status', 'abierto'); // Filtro por tickets abiertos
            });

        // Si el valor de perPage es 'all', obtener todos los tickets sin paginación
        $tickets = ($perPage == 'all') ? $ticketsQuery->get() : $ticketsQuery->paginate($perPage);

        return view('tickets.users.mytickets', [
            'tickets' => $tickets,
            'status' => $status,
            'perPage' => $perPage,
            'search' => $search,
        ]);
    }

    /**
     * Muestra un ticket y su historial de asignaciones
     *
     * Busca el ticket por su ID y muestra su título, descripción,
     * estado, fecha de creación y el historial de asignaciones
     * (técnicos asignados y fechas de inicio y fin de la
     * asignación).
     *
     * @param int $id El ID del ticket a mostrar
     * @return \Illuminate\Http\Response
     */
    public function Myticketsshow($id)
    {
        // Busca el ticket por su ID, junto con las asignaciones
        $ticket = Ticket::with('assignments.technician')->findOrFail($id);

        // Retorna la vista con el ticket y sus asignaciones (historial)
        return view('tickets.users.show', compact('ticket'));
    }

    /**
     * Asigna un técnico a un ticket y crea una asignación con los datos proporcionados.
     *
     * Esta función actualiza el ticket con la prioridad, técnico y estado proporcionados,
     * y crea una asignación con la tarea, fecha inicial y estado pendiente.
     *
     * @param \Illuminate\Http\Request $request La petición HTTP con los datos del formulario.
     * @param int $ticketId El ID del ticket que se va a asignar.
     *
     * @return \Illuminate\Http\RedirectResponse Una redirección a la ruta de asignación con un mensaje de éxito.
     */
    public function assignTicket(Request $request, $ticketId)
    {
        $request->validate([
            'priority' => 'required|string',
            'specialty_id' => 'required|exists:specialties,id',
            'technician_id' => 'required|exists:technicians,id',
            'task' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'priority.required' => 'La prioridad es requerida.',
            'specialty_id.required' => 'La especialidad es requerida.',
            'technician_id.required' => 'El técnico es requerido.',
            'task.required' => 'La tarea es requerida.',
            'start_date.required' => 'La fecha de inicio es requerida.',
            'end_date.required' => 'La fecha de fin es requerida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        // Actualizar el ticket con la nueva información
        $ticket->update([
            'priority' => $request->input('priority'),
            'technician_id' => $request->input('technician_id'),
            'status' => 'en progreso', // Cambiar el estado a 'en progreso'
        ]);

        // Crear una asignación
        Assignment::create([
            'technician_id' => $request->input('technician_id'),
            'ticket_id' => $ticket->id,
            'task' => $request->input('task'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => 'pendiente', // La asignación comienza como 'pendiente'
        ]);

        return redirect()->route('tickets.show', $ticketId)->with('success', 'Ticket asignado exitosamente.');
    }

    /**
     * Crea un nuevo título para los tickets
     *
     * Valida que el nombre del título sea único y tenga un máximo de 255 caracteres.
     * Luego, crea un nuevo título con el nombre proporcionado y lo guarda en la base de datos.
     * Finalmente, devuelve una respuesta en formato JSON con un mensaje de éxito.
     *
     * @param \Illuminate\Http\Request $request La petición HTTP con el nombre del título.
     * @return \Illuminate\Http\Response La respuesta en formato JSON con un mensaje de éxito.
     */
    public function titlestore(Request $request)
    {
        // Validación de datos
        $validatedData = $request->validate([
            'name' => 'required|string|unique:titles,name|max:255',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'El nombre ya existe.',
            'name.max' => 'El nombre no debe superar los 255 caracteres.',
        ]);

        try {
            // Guardar el título
            $title = new Title();
            $title->name = $validatedData['name'];
            $title->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar el título.'], 500);
        }
    }


    /**
     * Muestra la información de un ticket en particular.
     *
     * @param int $id El ID del ticket que se va a mostrar.
     * @return \Illuminate\Http\Response La vista con la información del ticket.
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }
}
