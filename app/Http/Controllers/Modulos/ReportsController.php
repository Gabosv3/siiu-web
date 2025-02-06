<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Technician; // Importar el modelo Technician
use App\Models\Departament;
use App\Models\Hardware;
use Illuminate\Http\Request;


class ReportsController extends Controller
{

    public function index()
    {
        return view('reports.index');
    }
    //
    /**
     * Muestra la vista de reportes por usuario.
     *
     * @return \Illuminate\View\View
     */
    public function getUserReports()
    {
        // Obtener todos los usuarios con sus relaciones
        $users = User::with(['departament', 'hardware', 'tickets'])->get();

        // Obtener todos los técnicos con su información de usuario
        $technicians = Technician::with('user')->get();

        // Obtener todos los departamentos
        $departments = Departament::all();

        // Obtener todos los equipos (hardware)
        $hardware = Hardware::all();

        // Pasar datos a la vista
        return view('reports.user_reports', compact('users', 'technicians', 'departments', 'hardware'));
    }

    /**
     * Genera el reporte por usuario en formato JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userReport(Request $request)
{
    try {
        // Validar los parámetros de la solicitud
        $request->validate([
            'userIds' => 'nullable|array',
            'userIds.*' => 'exists:users,id',  // Aseguramos que cada id de usuario sea válido
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'reportType' => 'required|in:usersByDepartment,ticketsByUser,hardwareByUser',
        ]);

        // Obtener parámetros del request
        $userIds = $request->input('userIds', []);
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $reportType = $request->input('reportType'); // Tipo de reporte

        // Inicializar variable $data
        $data = [];

        // Obtener los datos según el tipo de reporte seleccionado
        if ($reportType == 'usersByDepartment') {
            // Reporte de usuarios por departamento
            $data = Departament::withCount('users')->get();
        } elseif ($reportType == 'ticketsByUser') {
            // Reporte de tickets por usuario
            $query = User::withCount(['tickets' => function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }]);

            // Si hay usuarios seleccionados, filtramos por esos usuarios
            if (!empty($userIds) && !in_array('todos', $userIds)) {
                $query->whereIn('id', $userIds);
            }

            // Ejecutar la consulta
            $data = $query->get();
        } elseif ($reportType == 'hardwareByUser') {
            // Reporte de equipos asignados por usuario
            $query = User::withCount('hardware');

            // Si hay usuarios seleccionados, filtramos por esos usuarios
            if (!empty($userIds) && !in_array('todos', $userIds)) {
                $query->whereIn('id', $userIds);
            }

            // Ejecutar la consulta
            $data = $query->get();
        }

        // Devolver los datos en formato JSON
        return response()->json([
            'data' => $data,
            'reportType' => $reportType,  // Enviar el tipo de reporte para manejarlo en el frontend
        ]);
    } catch (\Exception $e) {
        // Capturar cualquier excepción y devolver un mensaje de error en JSON
        return response()->json([
            'error' => 'Error al generar el reporte',
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
