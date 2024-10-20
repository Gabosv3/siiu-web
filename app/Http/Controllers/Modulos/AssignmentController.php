<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Specialty;
use App\Models\Technician;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    //

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
        $request->validate([
            'priority' => 'required|string|in:alta,media,baja',
            'specialty_id' => 'required|exists:specialties,id',
            'technician_id' => 'required|exists:technicians,id',
            'task' => 'required|string',
            'initial_date' => 'required|date',
        ],
        [
            'priority.required' => 'El campo prioridad es obligatorio.',
            'specialty_id.required' => 'El campo especialidad es obligatorio.',
            'technician_id.required' => 'El campo técnico es obligatorio.',
            'task.required' => 'El campo tarea es obligatorio.',
            'initial_date.required' => 'El campo fecha inicial es obligatorio.',
        ]);
    
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
        ]);
    
        return redirect()->route('tickets.assignForm', $id)->with('success', 'Ticket asignado exitosamente.');
    }

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
