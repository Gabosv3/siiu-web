<?php

namespace App\Http\Controllers;

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
    public function Principal()
    {
        // Obtener el conteo total de usuarios
        $userCount = User::count();

        // Retorna la vista 'dashboard.Permisos' con el conteo de usuarios
        return view('dashboard.Permisos', compact('userCount'));
    }

    // Método para mostrar otra vista secundaria del dashboard
    public function Secundario()
    {
        // Retorna la vista 'dashboard.Secundario'
        return view('dashboard.Secundario');
    }
}
