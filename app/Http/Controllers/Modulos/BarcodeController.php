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
use ParagonIE\Sodium\Core\Curve25519\H;

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
        try {
            // Validar que el campo 'barcode' esté presente y exista en la base de datos
            $request->validate([
                'barcode' => 'required|exists:hardware,inventory_code'
            ], [
                'barcode.required' => 'El código de barras es obligatorio.',
                'barcode.exists' => 'El código de barras no existe.'
            ]);

            // Obtener el código de barras ingresado
            $codigo = $request->input('barcode');

            // Buscar todos los hardware que coincidan con el código de barras
            $hardwareItems = Hardware::where('inventory_code', $codigo);

            // Si se encuentran más de un hardware, aplicar paginación
            if ($hardwareItems->count() > 1) {
                $hardwareItems = $hardwareItems->paginate(10); // Cambia 10 por el número de elementos que desees por página
            } else {
                // Si solo hay uno o ninguno, obtener el hardware de manera directa
                $hardwareItems = $hardwareItems->get();
            }

            // Si no se encuentran elementos, redirigir con un mensaje de error
            if ($hardwareItems->isEmpty()) {
                return redirect()->back()->with('error', 'No se encontraron productos con ese código de barras.');
            }

            // Si solo se encuentra un hardware, redirigir a la vista de un solo hardware
            if ($hardwareItems->count() == 1) {
                // Solo uno, cargar el primer hardware encontrado
                $hardware = $hardwareItems->first();
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

                // Obtener licencias disponibles que pueden ser usadas en al menos un dispositivo
                $availableLicenses = License::whereRaw('(max_devices - used_devices) > 0')->get();

                // Obtener las licencias vinculadas al hardware
                $linkedLicenses = $hardware->softwares->flatMap(function ($software) {
                    return $software->licencias;
                });

                // Obtener solo los archivos relacionados con el hardware
                $hardwareFiles = $hardware->files()->get();

                // Retornar la vista de un solo hardware con los datos
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

            // Si hay más de un hardware, retornar a la vista con la lista de hardware encontrados
            return view('inventories.hardwares.multiple', compact('hardwareItems'));
        } catch (\Exception $e) {
            // Manejar cualquier excepción
            return redirect()->back()->with('error', 'Hubo un problema al procesar el código de barras. Intente nuevamente.');
        }
    }
}
