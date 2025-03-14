<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Departament;
use App\Models\User;
use Illuminate\Http\Request;

class DepartamentController extends Controller
{
    /**
     * Constructor del controlador.
     *
     * Establece middleware para controlar los permisos de acceso a los métodos
     * del controlador. Los middleware se aplican a los métodos según se indica
     * a continuación:
     *
     * - index: can:departamentos.index
     * - create y store: can:departamentos.create
     * - edit y update: can:departamentos.edit
     * - destroy: can:departamentos.destroy
     * - restore: can:departamentos.restore
     */
    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar los métodos específicos
        $this->middleware('can:departamentos.index')->only('index', 'show');
        $this->middleware('can:departamentos.create')->only('create', 'store');
        $this->middleware('can:departamentos.edit')->only('edit', 'update');
        $this->middleware('can:departamentos.destroy')->only('destroy');
        $this->middleware('can:departamentos.restore')->only('restore');
    }


    /**
     * Muestra una lista de todos los departamentos existentes en la base de datos
     *
     * @return \Illuminate\Http\Response
     */

    // Definir el número de elementos por página
    public function index(Request $request)
{
    // Obtener el valor de estado, búsqueda y registros por página
    $status = $request->get('status', 'active');
    $search = $request->get('search');
    $perPage = $request->get('perPage', 10);

    // Filtrar departamentos
    $departmentsQuery = Departament::query()
    ->when($search, function ($query, $search) {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('code', 'like', '%' . $search . '%');
        });
    })
    ->when($status == 'inactive', function ($query) {
        return $query->onlyTrashed();
    })
    ->when($status == 'active', function ($query) {
        return $query->whereNull('deleted_at');
    });

    // Si el valor de perPage es 'all', obtener todos los registros sin paginación
    if ($perPage == 'all') {
        $departments = $departmentsQuery->get(); // Sin paginación
    } else {
        $departments = $departmentsQuery->paginate($perPage); // Con paginación
    }

    return view('departments.index', [
        'departments' => $departments,
        'status' => $status,
        'perPage' => $perPage,
    ]);
}






    /**
     * Muestra el formulario para crear un nuevo departamento
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        // Pasar los usuarios a la vista
        return view('departments.create', compact('users'));
    }


    /**
     * Crea un nuevo departamento en la base de datos.
     *
     * Verifica que los datos del formulario sean válidos según las reglas de validación
     * definidas en la clase y crea un nuevo registro en la base de datos con los datos
     * proporcionados. Si el formulario contiene un ID de encargado, se obtiene el nombre
     * del encargado desde la tabla 'users' y se almacena en la base de datos. Finalmente,
     * se redirige a la lista de departamentos con un mensaje de éxito.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate(
            [
                'name' => 'required|string|max:255', // El nombre del departamento es obligatorio y debe ser una cadena
                'code' => 'required|unique:departaments,code|numeric', // El código es único, obligatorio y debe ser numérico
                'description' => 'required|string|max:500', // La descripción es obligatoria y debe ser una cadena con una longitud máxima
                'manager' => 'required|exists:users,id', // El encargado es obligatorio y debe existir en la tabla 'users'
                'latitude' => 'nullable|numeric', // La latitud es opcional y debe ser numérica
                'longitude' => 'nullable|numeric', // La longitud es opcional y debe ser numérica
            ],
            [
                'name.required' => 'El nombre del departamento es requerido.',
                'code.required' => 'El código del departamento es requerido.',
                'code.unique' => 'El código del departamento ya existe.',
                'code.numeric' => 'El código del departamento debe ser numérico.',
                'description.required' => 'La descripción del departamento es requerida.',
                'manager.required' => 'El encargado del departamento es requerido.',
                'manager.exists' => 'El encargado seleccionado no es valido.',
                'latitude.numeric' => 'La latitud debe ser numérica.',
                'longitude.numeric' => 'La longitud debe ser numérica.',
            ]
        );

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


    /**
     * Muestra los detalles de un departamento específico
     *
     * @param \App\Models\Departament $departament
     * @return \Illuminate\Http\Response
     */
    public function show(Departament $departament)
    {
        return view('departments.show', compact('departament'));
    }


    /**
     * Muestra el formulario para editar un departamento específico.
     *
     * @param \App\Models\Departament $departament El departamento que se va a editar.
     * @return \Illuminate\Http\Response La vista de edición del departamento con los datos del departamento y los usuarios asociados.
     */

    public function edit(Departament $departament)
    {
        $users = User::where('departament_id', $departament->id)->get();



        return view('departments.edit', compact('departament', 'users'));
    }


    /**
     * Actualiza un departamento específico en la base de datos.
     *
     * Verifica que los datos del formulario sean válidos según las reglas de validación
     * definidas en la clase y actualiza un registro existente en la base de datos
     * con los datos proporcionados. Si el formulario contiene un ID de encargado,
     * se obtiene el nombre del encargado desde la tabla 'users' y se almacena en
     * la base de datos. Finalmente, se redirige a la lista de departamentos con un
     * mensaje de éxito.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Departament  $departament
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Departament $departament)
    {
        // Validar los datos del formulario
        $request->validate(
            [
                'name' => 'required|string|max:255', // El nombre del departamento es obligatorio, debe ser una cadena y no puede exceder los 255 caracteres
                'code' => 'required|unique:departaments,code,' . $departament->id . '|numeric', // El código es único, obligatorio y debe ser numérico
                'description' => 'required|string|max:500', // La descripción es obligatoria, debe ser una cadena con una longitud máxima
                'manager' => 'required|exists:users,id', // El encargado es obligatorio y debe existir en la tabla 'users'
                'latitude' => 'nullable|numeric', // La latitud es opcional y debe ser numérica
                'longitude' => 'nullable|numeric', // La longitud es opcional y debe ser numérica
            ],
            [
                'name.required' => 'El nombre del departamento es requerido.',
                'code.required' => 'El código del departamento es requerido.',
                'code.unique' => 'El código del departamento ya existe.',
                'code.numeric' => 'El código del departamento debe ser numérico.',
                'description.required' => 'La descripción del departamento es requerida.',
                'manager.required' => 'El encargado del departamento es requerido.',
                'manager.exists' => 'El encargado seleccionado no es valido.',
                'latitude.numeric' => 'La latitud debe ser numérica.',
                'longitude.numeric' => 'La longitud debe ser numérica.',
            ]
        );

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


    /**
     * Elimina un departamento de la base de datos.
     *
     * Recibe el ID del departamento a eliminar y lo busca en la base de datos.
     * Si el departamento existe, se elimina y se redirige a la lista de departamentos
     * con un mensaje de éxito.
     *
     * @param int $id El ID del departamento a eliminar.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departamento = Departament::find($id);
        $departamento->delete(); // Eliminar el departamento de la base de datos
        // Redirigir a la lista de departamentos con un mensaje de éxito
        return redirect()->route('departaments.index')
            ->with('status', 'Departamento Eliminada con éxito.');
    }

    /**
     * Restaura un departamento eliminado de la base de datos.
     *
     * Recibe el ID del departamento eliminado y lo busca en la base de datos.
     * Si el departamento existe, se restaura y se redirige a la lista de departamentos
     * con un mensaje de éxito.
     *
     * @param int $id El ID del departamento eliminado a restaurar.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Muestra la lista de hardware asignado a un departamento específico.
     *
     * Recibe el ID del departamento y busca el registro en la base de datos.
     * Si el departamento existe, se obtiene la lista de hardware asociados
     * a ese departamento y se muestra en una vista con paginación de 10 elementos.
     *
     * @param int $departmentId El ID del departamento a buscar.
     *
     * @return \Illuminate\Http\Response La vista de la lista de hardware con paginación.
     */
    public function equipos($departmentId)
    {
        $departament = Departament::find($departmentId);

        // Paginación de 10 elementos
        $hardwares = $departament->hardwareAssignments()->with('hardware')->paginate(10);

        return view('inventories.hardwares.Equipoporfiltro', compact('hardwares', 'departament'));
    }
}
