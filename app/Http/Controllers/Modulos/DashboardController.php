<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\User;


class DashboardController extends Controller
{
    // Método para mostrar la vista principal del dashboard
    public function index()
    {
        // Retorna la vista 'dashboard.index'
        return view('dashboard.index');
    }

    // Método para mostrar la vista de permisos del dashboard
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

    // Método para mostrar otra vista secundaria del dashboard
    public function secundario()
    {
        // Retorna la vista 'dashboard.secundario'
        return view('dashboard.secundario');
    }

    // Método para mostrar la vista "Nosotros"
    public function nosotros()
    {
        // Retorna la vista 'components.nosotros'
        return view('components.nosotros');
    }
}
