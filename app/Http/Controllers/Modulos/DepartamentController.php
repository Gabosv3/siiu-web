<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Departament;
use App\Models\User;
use Illuminate\Http\Request;

class DepartamentController extends Controller
{
    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar los métodos específicos
        $this->middleware('can:departamentos.index')->only('index');
        $this->middleware('can:departamentos.create')->only('create', 'store');
        $this->middleware('can:departamentos.edit')->only('edit', 'update');
        $this->middleware('can:departamentos.destroy')->only('destroy');
        $this->middleware('can:departamentos.restore')->only('restore');
    }
    
    // Mostrar una lista de todos los departamentos
    public function index()
    {
        $deletedDepartments = Departament::onlyTrashed()->get();
        $departments = Departament::all(); // Obtener todos los departamentos de la base de datos
        return view('departments.index', compact('departments', 'deletedDepartments')); // Pasar los departamentos a la vista de índice
    }
    
    // Mostrar el formulario para crear un nuevo departamento
    public function create()
    {
        $users = User::all();
        // Pasar los usuarios a la vista
        return view('departments.create', compact('users'));
    }
    
    // Almacenar un nuevo departamento en la base de datos
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255', // El nombre del departamento es obligatorio y debe ser una cadena
            'code' => 'required|unique:departaments,code|numeric', // El código es único, obligatorio y debe ser numérico
            'description' => 'required|string|max:500', // La descripción es obligatoria y debe ser una cadena con una longitud máxima
            'manager' => 'required|exists:users,id', // El encargado es obligatorio y debe existir en la tabla 'users'
            'latitude' => 'nullable|numeric', // La latitud es opcional y debe ser numérica
            'longitude' => 'nullable|numeric', // La longitud es opcional y debe ser numérica
        ]);
    
        // Obtener el nombre del encargado si se proporciona un ID
        $inChargeName = null;
        if ($request->manager) {
            $manager = User::find($request->in_charge);
            if ($manager && $manager->informacionPersonal) {
                $inChargeName = $manager->informacionPersonal->nombres . ' ' . $manager->informacionPersonal->apellidos;
            }
        }
    
        // Crear un nuevo departamento en la base de datos
        Departament::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'manager' => $inChargeName,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
    
        // Redirigir a la lista de departamentos con un mensaje de éxito
        return redirect()->route('departaments.index')
            ->with('status', 'Departamento creado exitosamente.');
    }
    
    // Mostrar los detalles de un departamento específico
    public function show(Departament $departament)
    {
        return view('departments.show', compact('departament'));
    }
    
    // Mostrar el formulario para editar un departamento específico
    public function edit(Departament $departament)
    {
        $users = User::whereHas('personalInformation', function ($query) {
            $query->whereNotNull('first_name')
                ->whereNotNull('last_name');
        })->where('departament_id', $departament->id) // Corregir el campo del departamento aquí
            ->get();
    
        return view('departments.edit', compact('departament', 'users'));
    }
    
    // Actualizar un departamento específico en la base de datos
    public function update(Request $request, Departament $departament)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255', // El nombre del departamento es obligatorio, debe ser una cadena y no puede exceder los 255 caracteres
            'code' => 'required|unique:departaments,code,' . $departament->id . '|numeric', // El código es único, obligatorio y debe ser numérico
            'description' => 'required|string|max:500', // La descripción es obligatoria, debe ser una cadena con una longitud máxima
            'manager' => 'required|exists:users,id', // El encargado es obligatorio y debe existir en la tabla 'users'
            'latitude' => 'nullable|numeric', // La latitud es opcional y debe ser numérica
            'longitude' => 'nullable|numeric', // La longitud es opcional y debe ser numérica
        ]);
    
        $inChargeName = null;
        if ($request->manager) {
            $manager = User::find($request->manager);
            if ($manager && $manager->informacionPersonal) {
                $inChargeName = $manager->informacionPersonal->nombres . ' ' . $manager->informacionPersonal->apellidos;
            }
        }
    
        // Actualizar los datos del departamento en la base de datos
        $departament->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'manager' => $inChargeName,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
    
        // Redirigir a la lista de departamentos con un mensaje de éxito
        return redirect()->route('departaments.index')
            ->with('status', 'Departamento actualizado exitosamente.');
    }
    
    // Eliminar un departamento específico de la base de datos
    public function destroy($id)
    {

        $departamento = Departament::find($id);
        $departamento->delete(); // Eliminar el departamento de la base de datos

        
    
        // Redirigir a la lista de departamentos con un mensaje de éxito
        return redirect()->route('departaments.index')
            ->with('status', 'Departamento Eliminada con éxito.');
    }
    
    public function restore($id)
    {
        // Buscar el departamento eliminado por su ID
        $departamento = Departament::withTrashed()->find($id);
    
        if ($departamento) {
            // Restaurar el departamento eliminado
            $departamento->restore();
            return redirect()->back()->with('status', 'Departamento restaurado exitosamente.');
        } else {
            return redirect()->back()->with('status', 'Departamento no encontrado.');
        }
    }
    
}
