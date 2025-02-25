<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Departament;
use App\Models\EquipmentHistory;
use App\Models\Hardware;
use App\Models\License;
use App\Models\Manufacturer;
use App\Models\Models;
use App\Models\Software;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class HardwareController extends Controller
{
    /**
     * Constructor del controlador.
     *
     * Establece middleware para controlar los permisos de acceso a los métodos
     * del controlador. Los middleware se aplican a los métodos según se indica
     * a continuación:
     *
     * - index: can:hardware.index
     * - create y store: can:hardware.create
     * - edit y update: can:hardware.edit
     * - destroy: can:hardware.destroy
     * - restore: can:hardware.restore
     */
    public function __construct()
    {
        $this->middleware('can:hardware.index')->only('index');
        $this->middleware('can:hardware.create')->only('create', 'store');
        $this->middleware('can:hardware.edit')->only('edit', 'update');
        $this->middleware('can:hardware.destroy')->only('destroy');
        $this->middleware('can:hardware.restore')->only('restore');
    }
    public function index(Request $request)
    {
        $viewType = $request->input('view', 'card'); // Obtiene el tipo de vista desde la consulta, por defecto es 'card'
        $categoriaId = $request->input('category_id'); // Obtiene el ID de la categoría si está presente

        // Obtiene todos los registros de hardware y aplica el filtro por categoría si se proporciona
        $hardwares = Hardware::when($categoriaId && $categoriaId != 'all', function ($query) use ($categoriaId) {
            return $query->where('category_id', $categoriaId);
        })->paginate(16); // Obtiene 10 registros por categoria

        return view('inventories.hardwares.index', compact('hardwares', 'viewType'));
    }

    /**
     * Muestra la vista para crear un nuevo hardware.
     *
     * Si se proporciona un ID de categoría en la solicitud, se obtiene la categoría
     * correspondiente y se pasa a la vista.
     *
     * Si se ha enviado un archivo CSV en la solicitud, se almacena en la sesión
     * y se pasa a la vista.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Obtener las variables necesarias
        $fabricantes = Manufacturer::where('type', 'Equipo')->get();
        $categorias = Category::all();
        $usuarios = User::all();
        $departamentos = Departament::all();
        $modelos = Models::all();

        // Obtener la categoría con base en el ID proporcionado en la solicitud (si existe)
        $categoria = Category::find($request->input('category_id'));

        // Verifica si se ha enviado un archivo CSV
        $csvData = null;
        if ($request->session()->has('csv_data')) {
            $csvData = $request->session()->get('csv_data');
        }

        return view('inventories.hardwares.create', [
            'categoria' => $categoria,
            'fabricantes' => $fabricantes,
            'categorias' => $categorias,
            'usuarios' => $usuarios,
            'departamentos' => $departamentos,
            'modelos' => $modelos,
            'csv_data' => $csvData
        ]);
    }


    /**
     * Crea un nuevo hardware en la base de datos.
     *
     * Verifica que los datos sean válidos y crea un nuevo hardware
     * individualmente por cada conjunto de datos en el array.
     *
     * Adicionalmente, genera un código de barras para cada hardware
     * basado en su código de inventario y lo guarda como imagen
     * en el servidor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name.*' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|string',
            'departament_id.*' => 'nullable|exists:departaments,id',
            'inventory_code.*' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    foreach ($request->name as $index => $name) {
                        // Verifica si el inventory_code ya existe para esa categoría en la tabla 'hardware'
                        $exists = Hardware::where('category_id', $request->category_id)
                            ->where('inventory_code', $request->inventory_code[$index])
                            ->exists();

                        if ($exists) {
                            $fail('El código de inventario ' . $request->inventory_code[$index] . ' ya está registrado en esta categoría.');
                        }
                    }
                }
            ],
            'serial_number.*' => 'nullable|string|max:255|unique:hardware,serial_number',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'model_id' => 'nullable|exists:models,id',
            'warranty_expiration_date' => 'nullable|date',
            'user_id.*' => 'nullable|exists:users,id', // Validación para user_id (si está asignado)
        ]);

        // Generador de códigos de barras
        $barcodeGenerator = new BarcodeGeneratorPNG();

        // Recorremos cada conjunto de datos del array
        foreach ($request->name as $index => $name) {
            // Crear el código de barras basado en el código de inventario
            $inventoryCode = $request->inventory_code[$index];
            $barcode = $barcodeGenerator->getBarcode($inventoryCode, $barcodeGenerator::TYPE_CODE_128);

            // Guardar el código de barras como imagen en el servidor (por ejemplo, en la carpeta public/barcodes)
            $barcodePath = 'barcodes/' . $inventoryCode . '.png';
            Storage::disk('public')->put($barcodePath, $barcode);

            // Crear cada hardware individualmente usando los arrays
            Hardware::create([
                'name' => $name,
                'category_id' => $request->category_id,
                'status' => $request->status,
                'user_id' => $request->user_id[$index] ?? null,
                'location_id' => $request->location_id[$index] ?? null,
                'inventory_code' => $inventoryCode,
                'serial_number' => $request->serial_number[$index] ?? null,
                'manufacturer_id' => $request->manufacturer_id ?? null,
                'model_id' => $request->model_id ?? null,
                'warranty_expiration_date' => $request->warranty_expiration_date ?? null,
                'barcode_path' => $barcodePath, // Guardamos la ruta de la imagen del código de barras
            ]);
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('hardwares.index')->with('success', 'Hardware(s) creado(s) exitosamente con código de barras.');
    }

    /**
     * Muestra la vista para ver un hardware en particular.
     *
     * Carga las relaciones del hardware y los datos necesarios para mostrar
     * toda la información del hardware, incluyendo su historial de asignaciones,
     * software y licencias relacionadas, departamentos y usuarios con los
     * que se relaciona.
     *
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function show(Hardware $hardware)
    {
        // Cargar las relaciones del hardware, incluido el modelo asociado
        $hardware->load([
            'model.characteristics',
            'softwares.licencias',
            'equipmentHistories',
            'users',
            'category',
            'manufacturer'
        ]);

        // Obtener el historial de equipo del hardware
        $histories = EquipmentHistory::where('hardware_id', $hardware->id)->get();

        // Obtener todos los usuarios con información personal y departamento
        $users = User::with('personalInformation', 'departament')->get();

        // Obtener todos los departamentos
        $departaments = Departament::all();

        // Obtener todos los softwares gratuitos y de pago
        $freeSoftwares = Software::where('type', 'free')->get();
        $paidSoftwares = Software::where('type', 'paid')->get();

        // Obtener licencias disponibles que pueden ser usadas en al menos un dispositivo
        $availableLicenses = License::where('max_devices', '>=', 1)->get();

        // Obtener las licencias vinculadas al hardware
        $linkedLicenses = $hardware->softwares->flatMap(function ($software) {
            return $software->licencias;
        });

        // Obtener solo los archivos relacionados con el hardware
        $hardwareFiles = $hardware->files()->get();

        // Retornar la vista con todos los datos necesarios
        return view('inventories.hardwares.show', compact(
            'hardware',
            'users',
            'histories',
            'departaments',
            'freeSoftwares',
            'paidSoftwares',
            'availableLicenses',
            'linkedLicenses',
            'hardwareFiles'
        ));
    }





    /**
     * Muestra la vista para editar un hardware.
     *
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function edit(Hardware $hardware)
    {
        return view('inventories.hardwares.edit', compact('hardware'));
    }

    /**
     * Actualiza un hardware existente.
     *
     * Valida los datos del request y actualiza el hardware correspondiente.
     * Luego redirige a la lista de hardwares con un mensaje de éxito.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hardware $hardware)
    {

        return redirect()->route('hardwares.index')->with('success', 'Hardware actualizado exitosamente.');
    }

    /**
     * Elimina un hardware específico.
     *
     * Desvincula las etiquetas y sistemas relacionados antes de eliminar el hardware
     * de la base de datos. Luego redirige a la lista de hardwares con un mensaje de éxito.
     *
     * @param  \App\Models\Hardware  $hardware  El hardware a eliminar.
     * @return \Illuminate\Http\Response
     */

    public function destroy(Hardware $hardware)
    {
        $hardware->tags()->detach();
        $hardware->sistemas()->detach();
        $hardware->delete();

        return redirect()->route('hardwares.index')->with('success', 'Hardware eliminado exitosamente.');
    }

    /**
     * Restaura un hardware eliminado.
     *
     * Busca el hardware eliminado por su ID y lo restaura.
     * Luego redirige a la lista de hardwares con un mensaje de éxito.
     *
     * @param  \App\Models\Hardware  $hardware
     * @return \Illuminate\Http\Response
     */
    public function restore(Hardware $hardware)
    {
        $hardware->restore();

        return redirect()->route('hardwares.index')->with('success', 'Hardware restaurado exitosamente.');
    }
}
