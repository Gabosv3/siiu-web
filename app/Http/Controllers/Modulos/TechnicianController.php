<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Constructor del controlador.
     *
     * Establece middleware para controlar los permisos de acceso a los métodos
     * del controlador. Los middleware se aplican a los métodos según se indica
     * a continuación:
     *
     * - index: can:technicians.index
     * - create y store: can:technicians.create
     * - edit y update: can:technicians.edit
     * - destroy: can:technicians.destroy
     * - restore: can:technicians.restore
     */
    public function __construct()
    {
        $this->middleware('can:technicians.index')->only('index');
        $this->middleware('can:technicians.create')->only('create', 'store');
        $this->middleware('can:technicians.edit')->only('edit', 'update');
        $this->middleware('can:technicians.destroy')->only('destroy');
        $this->middleware('can:technicians.restore')->only('restore');
    }

    /**
     * Muestra la vista para crear un nuevo técnico
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtener los usuarios que no están relacionados con un técnico
        $users = User::whereDoesntHave('technician')->get();
        $specialties  = Specialty::all();

        // Retornar la vista 'user.technician.create' con los usuarios disponibles
        return view('user.technician.create', compact('users', 'specialties'));
    }

    /**
     * Crea un nuevo técnico
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        return redirect()->route('user.index')->with('tecnico_success', 'Técnico creado con éxito');

    }

    /**
     * Muestra la vista para editar un técnico
     *
     * @param int $id El id del técnico a editar
     * @return \Illuminate\Http\Response
     */
    public function edit($id){

        $technician = Technician::findOrFail($id);

        $specialties = Specialty::all();

        $users = User::whereDoesntHave('technician')->get();


        return view('user.technician.edit', compact('technician', 'specialties', 'users'));
    }

    /**
     * Actualiza un técnico
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id El id del técnico a actualizar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'specialty_id' => 'required|string|max:255',
            'available' => 'required|boolean',
        ],
        [
            'specialty_id.required' => 'La especialidad es requerida',
            'available.required' => 'El estado es requerido',
        ]);

        $technician = Technician::findOrFail($id);
        $technician->update([
            'specialty_id' => $validated['specialty_id'],
            'available' => $validated['available'],
        ]);

        return redirect()->route('user.index')->with('tecnico_success', 'Técnico actualizado con éxito');
    }

    
    /**
     * Elimina un técnico
     *
     * @param int $id El id del técnico a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $technician = Technician::findOrFail($id);
        $technician->delete();

        return redirect()->route('user.index')->with('tecnico_success', 'Técnico eliminado con éxito');
    }

    /**
     * Restaura un técnico eliminado
     *
     * @param int $id El id del técnico a restaurar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $technician = Technician::withTrashed()->findOrFail($id);
        $technician->restore();

        return redirect()->route('user.index')->with('tecnico_success', 'Técnico restaurado conxito');
    }


}
