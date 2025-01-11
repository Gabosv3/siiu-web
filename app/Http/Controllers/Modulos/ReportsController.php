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
    //
    public function getUserReports()
    {
        // Obtener usuarios y técnicos
        $users = User::all();
        $technicians = Technician::with('user')->get(); // Obtener técnicos 
        $departments = Departament::all();  // Cargar todos los departa
        // Obtener los equipos (hardware)
        $hardware = Hardware::all();

        // Pasar datos a la vista
        return view('reports.user_reports', compact('users', 'technicians', 'departments','hardware'));
    }
}
