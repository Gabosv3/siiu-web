<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Departament;
use App\Models\EquipmentHistory;
use App\Models\Hardware;
use App\Models\License;
use App\Models\Software;
use App\Models\User;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{

    /**
     * Mostrar la vista de escaneo de código de barras.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('Barcode.barcode_code');
    }
    
    
    /**
     * Procesa el código de barras ingresado, valida su existencia en la base de datos,
     * carga las relaciones del hardware correspondiente y retorna la vista con los datos
     * del hardware, su historial, usuarios, departamentos, software, licencias y archivos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */

    public function procesarCodigo(Request $request)
    {
        // Validar que el campo 'barcode' esté presente y exista en la base de datos
        $request->validate([
            'barcode' => 'required|exists:hardware,inventory_code'
        ], [
            'barcode.required' => 'El código de barras es obligatorio.',
            'barcode.exists' => 'El código de barras no existe.'
        ]);

        // Obtener el código de barras ingresado
        $codigo = $request->input('barcode');

        // Buscar el hardware en la base de datos
        $hardware = Hardware::where('inventory_code', $codigo)->first();

        // Si no se encuentra el hardware, redirigir con un mensaje de error
        if (!$hardware) {
            return redirect()->back()->with('error', 'Código de barras no encontrado.');
        }

        // Cargar relaciones del hardware
        $hardware->load([
            'model.characteristics',
            'softwares.licencias',
            'equipmentHistories',
            'users',
            'category',
            'manufacturer'
        ]);

        // Obtener el historial del hardware
        $histories = EquipmentHistory::where('hardware_id', $hardware->id)->get();

        // Obtener todos los usuarios con información personal y departamento
        $users = User::with('personalInformation', 'departament')->get();

        // Obtener todos los departamentos
        $departaments = Departament::all();

        // Obtener software gratuito y de pago
        $freeSoftwares = Software::where('type', 'free')->get();
        $paidSoftwares = Software::where('type', 'paid')->get();

        // Obtener licencias disponibles para al menos un dispositivo
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
}
