<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    //

    public function create()
    {
        // Obtener los usuarios que no están relacionados con un técnico
        $users = User::whereDoesntHave('technician')->get();
        $specialties  = Specialty::all();
    
        // Retornar la vista 'user.technician.create' con los usuarios disponibles
        return view('user.technician.create', compact('users', 'specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:technicians,user_id', // Asegura que el usuario no esté ya en técnicos
            'specialty_id' => 'required|string|max:255',
            'available' => 'required|boolean',
        ]);

        Technician::create([
            'user_id' => $validated['user_id'],
            'specialty_id' => $validated['specialty_id'],
            'available' => $validated['available'],
        ]);

        return redirect()->route('user.index')->with('success', 'Técnico creado con éxito');
    }

    public function edit($id){

        $technician = Technician::findOrFail($id);

        $specialties = Specialty::all();

        $users = User::whereDoesntHave('technician')->get();


        return view('user.technician.edit', compact('technician', 'specialties', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'specialty_id' => 'required|string|max:255',
            'available' => 'required|boolean',
        ]);

        $technician = Technician::findOrFail($id);
        $technician->update([
            'specialty_id' => $validated['specialty_id'],
            'available' => $validated['available'],
        ]);

        return redirect()->route('technician.index')->with('success', 'Técnico actualizado con éxito');
    }

    // Eliminar un técnico
    public function destroy($id)
    {
        $technician = Technician::findOrFail($id);
        $technician->delete();

        return redirect()->route('technician.index')->with('success', 'Técnico eliminado con éxito');
    }


}
