<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\User;


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
        // Obtener el conteo total de usuarios
        $userCount = User::count();

        // Obtener el conteo de usuarios por día
        $usersByDay = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Preparar las fechas y los conteos en arrays separados
        $dates = $usersByDay->pluck('date');
        $counts = $usersByDay->pluck('count');

        // Retornar la vista con el conteo de usuarios total y los datos por día
        return view('dashboard.permisos', compact('userCount', 'dates', 'counts'));
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
