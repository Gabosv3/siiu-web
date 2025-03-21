<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Hardware;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * Muestra la vista principal del dashboard, que muestra
     * información adicional o de utilidad para el usuario.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Retorna la vista 'dashboard.index'
        return view('dashboard.index');
    }


    /**
     * Muestra la vista principal del dashboard para usuarios con permisos administrativos.
     * 
     * @return \Illuminate\View\View
     */
    public function principal()
    {
        // Contador total de usuarios
        $userCount = User::count();

        // Usuarios creados por mes
        $usersByMonth = User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Extraer las fechas en formato YYYY-MM
        $dates = $usersByMonth->map(function ($item) {
            return "{$item->year}-" . str_pad($item->month, 2, '0', STR_PAD_LEFT);
        });

        // Extraer los conteos de usuarios
        $counts = $usersByMonth->pluck('count');

        // Cantidad total de tickets
        $totalTickets = Ticket::count();

        // Tickets abiertos y cerrados
        $openTickets = Ticket::where('status', 'abierto')->count();
        $closedTickets = Ticket::where('status', 'cerrado')->count();

        // Tickets por prioridad
        $ticketPriorities = Ticket::select('priority', DB::raw('count(*) as total'))
            ->where('status', 'en proceso') // Filtrar por status "en proceso"
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority');

        // Técnico con más tickets asignados
        $topTechnician = Technician::withCount('tickets')
            ->with('user') // Asegúrate de cargar la relación 'user'
            ->orderBy('tickets_count', 'desc')
            ->first();



        // Total de hardware
        $totalHardware = Hardware::count();

        $hardwareByCategory = Hardware::select('hardware.category_id', 'categories.name as category_name', DB::raw('count(*) as total'))
            ->join('categories', 'hardware.category_id', '=', 'categories.id') // Usar la tabla correcta 'categories'
            ->groupBy('hardware.category_id', 'categories.name') // Asegúrate de agrupar por 'category_id' y 'name'
            ->get();


        // Hardware con conflictos
        $hardwareWithConflicts = Hardware::whereNotNull('conflicts')->count();

        // Garantías activas
        $hardwareWithWarranty = Hardware::where('warranty_expiration_date', '>', now())->count();

        return view('dashboard.permisos', compact(
            'userCount',
            'dates',
            'counts',
            'totalTickets',
            'openTickets',
            'closedTickets',
            'ticketPriorities',
            'topTechnician',
            'totalHardware',
            'hardwareByCategory',
            'hardwareWithConflicts',
            'hardwareWithWarranty'
        ));
    }



    /**
     * Muestra la vista secundaria del dashboard, que muestra 
     * información adicional o de utilidad para el usuario.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function secundario()
    {
        // Retorna la vista 'dashboard.secundario'
        return view('dashboard.secundario');
    }


    /**
     * Muestra la vista "nosotros.blade.php", que muestra una vista adicional
     * con información sobre el equipo de desarrollo del proyecto.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function nosotros()
    {
        // Retorna la vista 'components.nosotros'
        return view('components.nosotros');
    }
}
