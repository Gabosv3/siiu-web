<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
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
        $departamentosDelets = Departamento::onlyTrashed()->get();
        $departamentos = Departamento::all(); // Obtiene todos los departamentos de la base de datos
        return view('departamentos.index', compact('departamentos', 'departamentosDelets')); // Pasa los departamentos a la vista index
    }

    // Mostrar el formulario para crear un nuevo departamento
    public function create()
    {
        $users = User::all();
        // Pasar los usuarios a la vista
        return view('departamentos.create', compact('users'));
    }

    // Almacenar un nuevo departamento en la base de datos
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255', // El nombre del departamento es obligatorio y debe ser una cadena
            'codigo' => 'nullable|unique:departamentos,codigo|numeric', // El código es único, opcional y debe ser numérico
            'descripcion' => 'nullable|string|max:500', // Descripción opcional y debe ser una cadena con longitud máxima
            'encargado' => 'nullable|exists:users,id', // Encargado opcional y debe existir en la tabla 'users'
            'latitude' => 'nullable|numeric', // Latitud opcional y debe ser numérico
            'longitude' => 'nullable|numeric', // Longitud opcional y debe ser numérico
        ]);

        // Obtener el nombre del encargado si se proporciona un ID
        $encargadoNombre = null;
        if ($request->encargado) {
            $encargado = User::find($request->encargado);
            if ($encargado && $encargado->informacionPersonal) {
                $encargadoNombre = $encargado->informacionPersonal->nombres . ' ' . $encargado->informacionPersonal->apellidos;
            }
        }


        // Crear un nuevo departamento en la base de datos
        Departamento::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
            'encargado' => $encargadoNombre,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);


        // Redirigir a la lista de departamentos con un mensaje de éxito
        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento creado exitosamente.');
    }

    // Mostrar los detalles de un departamento específico
    public function show(Departamento $departamento)
    {
        // Asegúrate de que la relación `encargado` está cargada
        $departamento->load('encargado');
        return view('departamentos.show', compact('departamento'));
    }

    // Mostrar el formulario para editar un departamento específico
    public function edit(Departamento $departamento)
    {
        $users = User::whereHas('informacionPersonal', function ($query) {
            $query->whereNotNull('nombres')
                ->whereNotNull('apellidos');
        })->where('departamento_id', $departamento->id) // Corrige aquí el campo del departamento
            ->get();

        return view('departamentos.edit', compact('departamento', 'users'));
    }

    // Actualizar un departamento específico en la base de datos
    public function update(Request $request, Departamento $departamento)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255', // El nombre del departamento es obligatorio, debe ser una cadena y no superar los 255 caracteres
            'codigo' => 'nullable|unique:departamentos,codigo,' . $departamento->id . '|numeric', // El código es único, opcional y debe ser numérico
            'descripcion' => 'nullable|string|max:500', // Descripción opcional, debe ser una cadena con longitud máxima
            'encargado' => 'nullable|exists:users,id', // Encargado opcional, debe existir en la tabla 'users'
            'latitude' => 'nullable|numeric', // Latitud opcional y debe ser numérico
            'longitude' => 'nullable|numeric', // Longitud opcional y debe ser numérico
        ]);

        $encargadoNombre = null;
        if ($request->encargado) {
            $encargado = User::find($request->encargado);
            if ($encargado && $encargado->informacionPersonal) {
                $encargadoNombre = $encargado->informacionPersonal->nombres . ' ' . $encargado->informacionPersonal->apellidos;
            }
        }
        // Actualizar los datos del departamento en la base de datos
        $departamento->update([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
            'encargado' => $encargadoNombre,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        // Redirigir a la lista de departamentos con un mensaje de éxito
        return redirect()->route('departamentos.index')
            ->with('status', 'Departamento actualizado exitosamente.');
    }

    // Eliminar un departamento específico de la base de datos
    public function destroy(Departamento $departamento)
    {
        $departamento->delete(); // Elimina el departamento de la base de datos

        // Redirigir a la lista de departamentos con un mensaje de éxito
        return redirect()->route('departamentos.index')
            ->with('eliminado', 'Departamento eliminado exitosamente.');
    }

    public function restore($id)
    {
        // Buscar el departamento eliminado por su ID
        $departamento = Departamento::withTrashed()->find($id);

        if ($departamento) {
            // Restaurar el departamento eliminado
            $departamento->restore();
            return redirect()->back()->with('status', 'Departamento restaurado exitosamente.');
        } else {
            return redirect()->back()->with('status', 'Departamento no encontrado.');
        }
    }
}
