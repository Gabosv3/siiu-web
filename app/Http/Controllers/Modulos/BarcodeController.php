<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Hardware;
use App\Models\User;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{

    public function index()
    {
        return view('Barcode.barcode_code');
    }
    //
    public function procesarCodigo(Request $request)
{
    // Validar que el campo 'barcode' esté presente y no esté vacío
    $request->validate([
        'barcode' => 'required|exists:hardware,inventory_code'
    ], [
        'barcode.required' => 'El código de barras es obligatorio.',
        'barcode.exists' => 'El código de barras no existe.']);

    // Obtener el valor del código de barras
    $codigo = $request->input('barcode');

    // Buscar el código de barras en la base de datos
    $hardware = Hardware::where('inventory_code', $codigo)->first();
    $users = User::with('personalInformation', 'departament')->get();

    if ($hardware) {
        // Aquí puedes hacer lo que necesites con el código, como mostrar detalles del producto
        return view('inventories.hardwares.show', compact('hardware', 'users'));
    } else {
        return redirect()->back()->with('error', 'Código de barras no encontrado');
    }
}

}
