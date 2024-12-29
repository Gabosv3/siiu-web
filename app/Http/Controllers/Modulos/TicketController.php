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

    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->get();

        return view('tickets.index', compact('tickets'));
    }

    public function crearTicketindex()
    {
        $titles = Title::all();
        return view('tickets.users.create', compact('titles'));
    }
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

    public function Mytickets()
    {
        $tickets = auth()->user()->tickets()->get();
        return view('tickets.users.mytickets', compact('tickets'));
    }

    public function Myticketsshow($id)
    {
        // Busca el ticket por su ID, junto con las asignaciones
        $ticket = Ticket::with('assignments.technician')->findOrFail($id);

        // Retorna la vista con el ticket y sus asignaciones (historial)
        return view('tickets.users.show', compact('ticket'));
    }







    public function assignTicket(Request $request, $ticketId)
    {
        $request->validate([
            'priority' => 'required|string',
            'specialty_id' => 'required|exists:specialties,id',
            'technician_id' => 'required|exists:technicians,id',
            'task' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
}
