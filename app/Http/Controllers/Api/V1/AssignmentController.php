<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    //
    public function index()
    {
        // Obtener el id del técnico
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
            return response()->json(['message' => 'No hay asignaciones disponibles.'], 404);
        }

        // Mapear los resultados
        $mappedAssignments = $assignments->map(function ($assignment) {
            return [
                'title' => $assignment->technician->user->name . ' - ' . $assignment->task,
                'start' => $assignment->initial_date, // Usar 'initial_date' desde assignments
            ];
        });

        return response()->json($mappedAssignments);
    }
}
