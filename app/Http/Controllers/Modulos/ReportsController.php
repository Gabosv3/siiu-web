<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Technician; // Importar el modelo Technician
use App\Models\Departament;
use App\Models\Hardware;
use App\Models\Manufacturer;
use App\Models\Models;
use App\Models\Supply;
use App\Models\Ticket;
use App\Models\Title;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

class ReportsController extends Controller
{
    /**public function __construct()
    {
        $this->middleware('can:reports.index')->only('index');
        $this->middleware('can:reports.user_reports')->only('getUserReports');
    }**/

    /**
     * Muestra la vista principal del módulo de reportes.
     *
     * @return \Illuminate\Contracts\View\View
     */

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

    public function getInsumosReport()
    {
        $categories = Category::all();  // Obtener todas las categorías
        $manufacturers = Manufacturer::all();  // Obtener todos los fabricantes
        $models = Models::all();  // Obtener todos los modelos

        // Pasar los datos a la vista
        return view('reports.insumos_report', compact('categories', 'manufacturers', 'models'));
    }

    // Obtener los datos filtrados del reporte de suministros
    public function getSupplyData(Request $request)
    {
        try {
            // Validar los parámetros de la solicitud
            $request->validate([
                'startDate' => 'nullable|date',
                'endDate' => 'nullable|date|after_or_equal:startDate',
                'insumoType' => 'nullable|in:byCategory,byManufacturer,byModel,byStatus',
                'status' => 'nullable|in:active,inactive',  // Si necesitas validar el estado del insumo
            ]);

            // Obtener parámetros del request
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $insumoType = $request->input('insumoType');  // Tipo de insumo
            $status = $request->input('status'); // Estado de insumo

            // Inicializar la variable $query
            $query = Supply::query();



            // Filtrar por tipo de insumo
            if ($insumoType) {
                if ($insumoType === 'byCategory') {
                    $query->select(
                        'supplies.category_id',
                        'categories.name as category_name', // Unir la tabla `categories` y seleccionar el nombre
                        'supplies.name as supply_name', // Agregar el nombre del supply
                        DB::raw('SUM(supplies.quantity) as total_quantity'),
                        DB::raw('MAX(supplies.unit) as unit'),
                        DB::raw('MAX(supplies.description) as description'),
                        DB::raw('MAX(supplies.status) as status')
                    )
                        ->join('categories', 'supplies.category_id', '=', 'categories.id') // Hacer el join con `categories`
                        ->groupBy('supplies.category_id', 'categories.name', 'supplies.name'); // Incluir 'supplies.name' en el GROUP BY

                } elseif ($insumoType === 'byManufacturer') {
                    $query->select(
                        'supplies.manufacturer_id',
                        'manufacturers.name as manufacturer_name', // Unir la tabla `manufacturers` y seleccionar el nombre
                        'supplies.name as supply_name',
                        DB::raw('SUM(supplies.quantity) as total_quantity'),
                        DB::raw('MAX(supplies.unit) as unit'),
                        DB::raw('MAX(supplies.description) as description'),
                        DB::raw('MAX(supplies.status) as status')
                    )
                        ->join('manufacturers', 'supplies.manufacturer_id', '=', 'manufacturers.id') // Hacer el join con `manufacturers`
                        ->groupBy('supplies.manufacturer_id', 'manufacturers.name', 'supplies.name'); // Incluir 'supplies.name' en el GROUP BY

                } elseif ($insumoType === 'byModel') {
                    $query->select(
                        'supplies.model_id',
                        'models.name as model_name', // Unir la tabla `models` y seleccionar el nombre
                        'supplies.name as supply_name',
                        DB::raw('SUM(supplies.quantity) as total_quantity'),
                        DB::raw('MAX(supplies.unit) as unit'),
                        DB::raw('MAX(supplies.description) as description'),
                        DB::raw('MAX(supplies.status) as status')
                    )
                        ->join('models', 'supplies.model_id', '=', 'models.id') // Hacer el join con `models`
                        ->groupBy('supplies.model_id', 'models.name', 'supplies.name'); // Incluir 'supplies.name' en el GROUP BY

                } elseif ($insumoType === 'byStatus' && $status) {
                    $query->select(
                        'supplies.status',
                        'supplies.name as supply_name',
                        DB::raw('SUM(supplies.quantity) as total_quantity'),
                        DB::raw('MAX(supplies.unit) as unit'),
                        DB::raw('MAX(supplies.description) as description')
                    )
                        ->where('supplies.status', $status)
                        ->groupBy('supplies.status', 'supplies.name'); // Incluir 'supplies.name' en el GROUP BY
                }
            }


            // Ejecutar la consulta
            $insumos = $query->get();

            // Devolver los datos en formato JSON
            return response()->json([
                'data' => $insumos,
                'reportType' => $insumoType,  // Enviar el tipo de reporte para manejarlo en el frontend
            ]);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y devolver un mensaje de error en JSON
            return response()->json([
                'error' => 'Error al generar el reporte',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Generar el reporte en PDF o Excel
    public function generateReport(Request $request)
    {
        $categoryIds = $request->input('categoryIds', []);
        $manufacturerIds = $request->input('manufacturerIds', []);
        $modelIds = $request->input('modelIds', []);
        $status = $request->input('status', null);
        $type = $request->input('type');  // Puede ser 'pdf' o 'excel'

        $query = Supply::query();

        // Filtros de categoría
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        // Filtros de fabricante
        if (!empty($manufacturerIds)) {
            $query->whereIn('manufacturer_id', $manufacturerIds);
        }

        // Filtros de modelo
        if (!empty($modelIds)) {
            $query->whereIn('model_id', $modelIds);
        }

        // Filtro de estado
        if ($status !== null) {
            $query->where('status', $status);
        }

        $supplies = $query->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('supplies.report', compact('supplies'));
            return $pdf->download('reporte_insumos.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new SuppliesExport($supplies), 'reporte_insumos.xlsx');
        }

        return redirect()->back()->withErrors('Tipo de reporte no soportado');
    }

    public function getTicketReports()
    {
        $titles = Title::all(); // Obtener todos los títulos
        $categories = Category::all();  // Obtener todas las categorías
        $manufacturers = Manufacturer::all();  // Obtener todos los fabricantes
        $models = Models::all();  // Obtener todos los modelos

        // Pasar los datos a la vista
        return view('reports.tickets_report', compact('titles', 'manufacturers', 'models'));
    }

    public function getTicketData(Request $request)
    {

        // Definir la consulta base
        $tickets = Ticket::query();

        // Filtrar por tipo de ticket
        $ticketType = $request->ticketType;

        // Filtrar por tipo de ticket (como por ejemplo 'byTitle', 'byTechnician', etc.)
        switch ($ticketType) {
            case 'byTitle':
                // Agrupar por título y obtener el nombre del título
                $tickets = $tickets->join('titles', 'tickets.title_id', '=', 'titles.id')
                    ->selectRaw('titles.name as ticket_name, count(*) as total_tickets')
                    ->groupBy('titles.name')
                    ->get();
                break;

            case 'byTechnician':
                // Agrupar por técnico y obtener el nombre del técnico desde la tabla users
                $tickets = $tickets->join('technicians', 'tickets.technician_id', '=', 'technicians.id')
                    ->join('users', 'technicians.user_id', '=', 'users.id')  // Unimos la tabla users para obtener el nombre
                    ->select('users.name as technician_name', DB::raw('count(*) as total_tickets'))  // Seleccionamos el nombre del técnico y el total de tickets
                    ->groupBy('users.name')  // Agrupamos por nombre del técnico
                    ->get();
                break;

            case 'byUser':
                // Agrupar por usuario
                $tickets = $tickets->join('users', 'tickets.user_id', '=', 'users.id')
                    ->selectRaw('users.name as user_name, count(*) as total_tickets')
                    ->groupBy('users.name')
                    ->get();
                break;

            case 'byAssignment':
                // Agrupar por asignación y obtener el nombre del técnico asignado
                $tickets = $tickets->join('assignments', 'tickets.id', '=', 'assignments.ticket_id')
                    ->join('technicians', 'assignments.technician_id', '=', 'technicians.id')  // Unimos con la tabla technicians para obtener el técnico
                    ->join('users', 'technicians.user_id', '=', 'users.id')  // Unimos con la tabla users para obtener el nombre del técnico
                    ->select('users.name as technician_name', DB::raw('count(*) as total_tickets'))  // Seleccionamos el nombre del técnico y la cantidad de tickets
                    ->groupBy('users.name')  // Agrupamos por el nombre del técnico
                    ->get();
                break;


            case 'byPriority':
                // Agrupar por prioridad
                $tickets = $tickets->selectRaw('priority, count(*) as total_tickets') // Usando 'priority' del modelo Ticket
                    ->groupBy('priority')
                    ->get();
                break;

            case 'byStatus':
                // Agrupar por estado
                $tickets = $tickets->selectRaw('status, count(*) as total_tickets')
                    ->groupBy('status')
                    ->get();
                break;

            default:
                // Si no se especifica un tipo, devolver todos los tickets sin agrupar
                $tickets = $tickets->get();
                break;
        }

        return response()->json([
            'data' => $tickets,
            'reportType' => $ticketType,
        ]);
    }
}
