<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\EquipmentSoftware;
use App\Models\Hardware;
use App\Models\License;
use App\Models\Software;
use Illuminate\Http\Request;


class LicenseController extends Controller
{
    /**
     * Constructor del controlador.
     *
     * Establece middleware para controlar los permisos de acceso a los métodos
     * del controlador. Los middleware se aplican a los métodos según se indica
     * a continuación:
     *
     * - index: can:licencias.index
     * - create y store: can:licencias.create
     * - edit y update: can:licencias.edit
     * - destroy: can:licencias.destroy
     * - restore: can:licencias.restore
     *
     */
    public function __construct()
    {
        $this->middleware('can:licencias.index')->only('index');
        $this->middleware('can:licencias.create')->only('create', 'store');
        $this->middleware('can:licencias.edit')->only('edit', 'update');
        $this->middleware('can:licencias.destroy')->only('destroy');
        $this->middleware('can:licencias.restore')->only('restore');
    }

    /**
     * Muestra la lista de licencias, con opción de filtrar por software.
     *
     * Si se proporciona el ID de un software en el query string,
     * se muestran solo las licencias para ese software.
     * Si no se proporciona, se muestran todas las licencias.
     *
     * @param \Illuminate\Http\Request $request La solicitud que contiene el ID del software.
     * @return \Illuminate\Http\Response La respuesta con la vista de la lista de licencias.
     */
    public function index(Request $request)
    {
        $licesesdeleted = License::onlyTrashed()->get();
        // Obtiene el software_id del query string si está presente
        $software_id = $request->input('software_id');

        // Obtiene todos los softwares para el dropdown
        $softwares = Software::where('type', 'paid')->get();

        if ($software_id) {
            // Obtiene el software correspondiente
            $software = Software::find($software_id);

            // Obtiene las licencias para el software específico
            $licenses = License::where('software_id', $software_id)->get();
        } else {
            // Si no hay software_id, obtiene todas las licencias
            $licenses = License::all();
            $software = null; // No hay software seleccionado
        }

        return view('inventories.licenses.index', compact('licenses', 'software', 'softwares', 'licesesdeleted'));
    }


    /**
     * Muestra la vista para crear una nueva licencia.
     *
     * Obtiene el software correspondiente a partir del ID proporcionado
     * en la solicitud y lo pasa a la vista de creación de licencias.
     *
     * @param \Illuminate\Http\Request $request La solicitud que contiene el ID del software.
     * @return \Illuminate\View\View La vista para crear una nueva licencia.
     */

    public function create(Request $request)
    {
        $software_id = $request->input('software_id');

        // Obtiene el software correspondiente
        $software = Software::find($software_id);

        return view('inventories.licenses.create', compact('software'));
    }


    /**
     * Crea una nueva licencia para un software.
     *
     * Valida los datos de la solicitud y crea una o varias licencias
     * según la cantidad de claves de licencia proporcionadas.
     * Si la validación falla, se muestra un mensaje de error.
     * Si la creación es exitosa, se muestra un mensaje de éxito.
     *
     * @param \Illuminate\Http\Request $request La solicitud que contiene los datos de la licencia a crear.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la lista de licencias.
     */
    public function store(Request $request)
    {
        $request->validate([
            'software_id' => 'required|exists:softwares,id',
            'license_key.*' => 'required|string|unique:licenses,license_key',
            'max_devices.*' => 'nullable|integer|min:0|max:100',  // Cambiado de max_licenses a max_devices
            'purchase_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|string',
        ], [
            'software_id.exists' => 'El software no existe.',
            'license_key.*.unique' => 'La clave de licencia ":input" ya está registrada.',
            'license_key.*.required' => 'La clave de licencia es obligatoria.',
            'max_devices.*.integer' => 'El número de dispositivos debe ser un número entero.', // Cambiado max_licenses a max_devices
            'max_devices.*.min' => 'El número de dispositivos debe ser al menos 0.', // Cambiado max_licenses a max_devices
            'max_devices.*.max' => 'El número de dispositivos no puede ser mayor a 100.', // Cambiado max_licenses a max_devices
            'purchase_date.date' => 'La fecha de compra no es una fecha válida.',
            'expiration_date.date' => 'La fecha de expiración no es una fecha válida.',
            'expiration_date.after_or_equal' => 'La fecha de expiración debe ser posterior o igual a la fecha de compra.',
            'status.required' => 'El estado es requerido.',
            'status.string' => 'El estado debe ser una cadena de texto.',
        ]);

        foreach ($request->license_key as $index => $license_key) {
            License::create([
                'software_id' => $request->software_id,
                'license_key' => $license_key,
                'max_devices' => !empty($request->max_devices[$index]) ? $request->max_devices[$index] : 1, // Asegurar valor
                'purchase_date' => $request->purchase_date,
                'expiration_date' => $request->expiration_date,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('licenses.index', ['software_id' => $request->software_id])
            ->with('success', 'Licencia creada exitosamente.');
    }

    /**
     * Muestra la vista de detalles de una licencia.
     *
     * @param \App\Models\License $license La licencia a mostrar.
     * @return \Illuminate\View\View La vista de detalles de la licencia.
     */
    public function show(License $license)
    {
        return view('inventories.licenses.show', compact('license'));
    }

    /**
     * Muestra la vista de edición de una licencia.
     *
     * @param \App\Models\License $license La licencia a editar.
     * @return \Illuminate\View\View La vista de edición de la licencia.
     */
    public function edit(License $license)
    {
        $softwares = Software::all();
        $equipos = Hardware::all();
        return view('inventories.licenses.edit', compact('license', 'softwares', 'equipos'));
    }
    /**
     * Actualiza una licencia.
     *
     * Valida los datos de la solicitud y actualiza la licencia correspondiente.
     * Si la validación falla, se muestra un mensaje de error.
     * Si la actualización es exitosa, se muestra un mensaje de éxito.
     *
     * @param \Illuminate\Http\Request $request La solicitud que contiene los datos de la licencia a actualizar.
     * @param \App\Models\License $license La licencia a actualizar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la lista de licencias.
     */
    public function update(Request $request, License $license)
    {
        $request->validate([
            'software_id' => 'required|exists:softwares,id',
            'license_key' => 'required|unique:licenses,license_key,' . $license->id,
            'expiration_date' => 'nullable|date',

        ], [
            'software_id.exists' => 'El software no existe.',
            'license_key.unique' => 'La clave de licencia ya existe.',
            'expiration_date.date' => 'La fecha de expiración no es una fecha válida.',
            'expiration_date.after_or_equal' => 'La fecha de expiración debe ser posterior o igual a la fecha de compra.',
        ]);

        $license->update($request->all());

        return redirect()->route('licenses.index')->with('success', 'Licencia actualizada exitosamente.');
    }

    /**
     * Elimina una licencia.
     *
     * Elimina la licencia solicitada de la base de datos.
     * Si la eliminación es exitosa, se muestra un mensaje de éxito.
     * Si la eliminación falla, se muestra un mensaje de error.
     *
     * @param \App\Models\License $license La licencia a eliminar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la lista de licencias.
     */
    public function destroy(License $license)
    {
        if ($license->delete()) {
            return redirect()->route('licenses.index')->with('success', 'Licencia eliminada exitosamente.');
        }

        return redirect()->route('licenses.index')->with('error', 'Licencia no encontrada.');
    }

    /**
     * Restaura una licencia eliminada.
     *
     * Busca la licencia eliminada por su ID y la restaura.
     * Luego redirige a la lista de licencias con un mensaje de éxito.
     * Si la licencia no se encuentra, redirige con un mensaje de error.
     *
     * @param int $id El ID de la licencia a restaurar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la lista de licencias.
     */

    public function restore($id)
    {
        $license = License::withTrashed()->find($id);
        if (!$license) {
            return redirect()->route('licenses.index')->with('error', 'Licencia no encontrada.');
        }
        $license->restore();
        return redirect()->route('licenses.index')->with('success', 'Licencia restaurada exitosamente.');
    }

    public function linkLicense(Request $request)
    {
        // Validar los datos
        $request->validate([
            'software_id' => 'required|exists:softwares,id',
            'license_id' => 'nullable|exists:licenses,id', // La licencia puede ser opcional para software gratuito
            'equipment_id' => 'required|exists:hardware,id', // Asegurarse que el equipo existe
        ]);

        // Obtener el equipo, software y licencia
        $equipment = Hardware::findOrFail($request->equipment_id);
        $software = Software::findOrFail($request->software_id);
        $license = $request->license_id ? License::findOrFail($request->license_id) : null;

        // Si el software tiene una licencia de pago, verificar disponibilidad
        if ($license) {
            // Verificar si hay dispositivos disponibles
            if ($license->used_devices >= $license->max_devices) {
                return back()->withErrors(['license' => 'No hay dispositivos disponibles para esta licencia.']);
            }

            // Incrementar el contador de dispositivos en uso
            $license->increment('used_devices');
        }

        // Guardar la vinculación en la tabla equipment_softwares
        EquipmentSoftware::create([
            'hardware_id' => $equipment->id,
            'software_id' => $software->id,
            'license_id' => $license ? $license->id : null, // Si hay licencia, se guarda
        ]);

        return redirect()->route('hardwares.show', $equipment->id)
            ->with('success', 'Licencia vinculada exitosamente.');
    }
}
