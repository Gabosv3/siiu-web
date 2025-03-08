<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\Software;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:softwares.index')->only('index');
        $this->middleware('can:softwares.create')->only('create', 'store');
        $this->middleware('can:softwares.edit')->only('edit', 'update');
        $this->middleware('can:softwares.destroy')->only('destroy');
        $this->middleware('can:softwares.restore')->only('restore');
    }
    /**
     * Muestra una lista de todos los softwares, incluyendo los eliminados,
     * con sus respectivos fabricantes y licencias.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Obtener filtros de la solicitud
        $status = $request->get('status', 'active');
        $search = $request->get('search');
        $perPage = $request->get('perPage', 10);

        // Construcción de la consulta
        $softwaresQuery = Software::with('manufacturer')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('software_name', 'like', '%' . $search . '%')
                        ->orWhere('version', 'like', '%' . $search . '%');
                });
            })
            ->when($status === 'inactive', function ($query) {
                return $query->onlyTrashed();
            })
            ->when($status === 'active', function ($query) {
                return $query->whereNull('deleted_at');
            });

        // Si el valor de perPage es 'all', obtener todos sin paginar
        $softwares = ($perPage == 'all') ? $softwaresQuery->get() : $softwaresQuery->paginate($perPage);

        return view('inventories.softwares.index', [
            'softwares' => $softwares,
            'status' => $status,
            'perPage' => $perPage,
            'search' => $search,
        ]);
    }




    /**
     * Muestra la vista para crear un nuevo software.
     * Recibe un objeto Manufacturer como parámetro y utiliza el método compact para
     * pasar los datos a la vista.
     * La vista create.blade.php utiliza el fabricante para mostrar
     * los datos del fabricante en un select.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fabricantes = Manufacturer::where('type', 'Software')->get();
        return view('inventories.softwares.create', compact('fabricantes'));
    }



    /**
     * Crea un nuevo software en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'manufacturer_id' => 'required|exists:manufacturers,id',
                'software_name' => 'required|string|max:255',
                'version' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            [
                'manufacturer_id.required' => 'El fabricante es obligatorio',
                'software_name.required' => 'El nombre del software es obligatorio',
                'version.required' => 'La versión es obligatoria',
                'type.required' => 'El tipo es obligatorio',
                'description.required' => 'La descripción es obligatoria',
                'description.string' => 'La descripción debe ser una cadena de texto',
                'type.string' => 'El tipo debe ser una cadena de texto',
                'type.max' => 'El tipo no debe superar los 255 caracteres',
                'description.max' => 'La descripción no debe superar los 65535 caracteres',
            ]
        );

        Software::create($validatedData);

        return redirect()->route('softwares.index')->with('success', 'Software creado exitosamente');
    }

    //
    // Muestra la vista para editar un software.
    // Recibe un objeto Software como parámetro y utiliza el método compact para
    // pasar los datos a la vista.
    // La vista edit.blade.php utiliza el software y los fabricantes para mostrar
    // los datos del software y un select para elegir el fabricante.
    //
    //
    public function edit(Software $software)
    {
        $fabricantes = Manufacturer::all();
        return view('inventories.softwares.edit', compact('software', 'fabricantes'));
    }



    /**
     * Actualiza un software existente.
     *
     * Valida los datos del request y actualiza el software correspondiente.
     * Luego redirige a la lista de softwares con un mensaje de éxito.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Software $software)
    {
        $validatedData = $request->validate(
            [
                'manufacturer_id' => 'required|exists:manufacturers,id',
                'software_name' => 'required|string|max:255',
                'version' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            [
                'manufacturer_id.required' => 'El fabricante es obligatorio',
                'software_name.required' => 'El nombre del software es obligatorio',
                'version.required' => 'La versión es obligatoria',
                'type.required' => 'El tipo es obligatorio',
                'description.required' => 'La descripción es obligatoria',
                'description.string' => 'La descripción debe ser una cadena de texto',
                'type.string' => 'El tipo debe ser una cadena de texto',
                'type.max' => 'El tipo no debe superar los 255 caracteres',
                'description.max' => 'La descripción no debe superar los 65535 caracteres',
            ]
        );

        $software->update($validatedData);

        return redirect()->route('softwares.index')->with('success', 'Software actualizado exitosamente');
    }


    /**
     * Muestra los detalles de un software.
     *
     * Muestra la vista con los detalles del software solicitado, incluyendo
     * sus licencias y el total de licencias.
     *
     * @param int $id La ID del software a mostrar.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Encontrar el software por su ID
        $software = Software::findOrFail($id);

        // Obtener las licencias vinculadas a este software
        $licencias = $software->licencias;

        // Contar cuántas licencias tiene este software
        $totalLicencias = $licencias->count();

        // Retornar la vista con el software, las licencias y el total de licencias
        return view('inventories.softwares.show', compact('software', 'licencias', 'totalLicencias'));
    }


    /**
     * Elimina un software.
     *
     * Elimina el software solicitado y todos sus registros relacionados.
     *
     * @param \App\Models\Software $software El software a eliminar.
     * @return \Illuminate\Http\Response
     */
    public function destroy(Software $software)
    {
        $software->delete();

        // Registro en el log
        if ($software) {
            return redirect()->route('softwares.index')->with('success', 'Software eliminado exitosamente');
        }

        return redirect()->route('softwares.index')->with('error', 'Software no encontrado');
    }



    /**
     * Restaura un software eliminado.
     *
     * Busca el software eliminado por su ID y lo restaura.
     * Luego redirige a la lista de softwares con un mensaje de éxito.
     *
     * @param int $id La ID del software a restaurar.
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        // Buscar el software eliminado por su ID
        $software = Software::withTrashed()->find($id);

        if ($software) {
            // Restaurar el software eliminado
            $software->restore();
            return redirect()->back()->with('success', 'Software restaurado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'Software no encontrado.');
        }
    }
}
