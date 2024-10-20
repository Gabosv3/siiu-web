<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Departament;
use App\Models\Hardware;
use App\Models\Manufacturer;
use App\Models\Models;
use App\Models\Software;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class HardwareController extends Controller
{
    public function index(Request $request)
    {
        $viewType = $request->input('view', 'card'); // Obtiene el tipo de vista desde la consulta, por defecto es 'card'
        $categoriaId = $request->input('category_id'); // Obtiene el ID de la categoría si está presente

        // Obtiene todos los registros de hardware y aplica el filtro por categoría si se proporciona
        $hardwares = Hardware::when($categoriaId && $categoriaId != 'all', function ($query) use ($categoriaId) {
            return $query->where('category_id', $categoriaId);
        })->paginate(16); // Cambia 12 por el número de elementos que quieras por página

        return view('inventories.hardwares.index', compact('hardwares', 'viewType'));
    }

    public function create(Request $request)
    {
        // Obtener las variables necesarias
        $fabricantes = Manufacturer::all();
        $categorias = Category::all();
        $usuarios = User::all();
        $departamentos = Departament::all();
        $modelos = Models::all();
        $tags = Tag::all();
        $sistemas = Software::all();

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
            'tags' => $tags,
            'sistemas' => $sistemas,
            'csv_data' => $csvData
        ]);
    }


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
                function($attribute, $value, $fail) use ($request) {
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

    public function show(Hardware $hardware)
    {
        return view('inventario.hardware.show', compact('hardware'));
    }

    public function edit(Hardware $hardware)
    {
        return view('inventories.hardwares.edit', compact('hardware'));
    }

    public function update(Request $request, Hardware $hardware)
    {

        return redirect()->route('hardwares.index')->with('success', 'Hardware actualizado exitosamente.');
    }

    public function destroy(Hardware $hardware)
    {
        $hardware->tags()->detach();
        $hardware->sistemas()->detach();
        $hardware->delete();

        return redirect()->route('hardwares.index')->with('success', 'Hardware eliminado exitosamente.');
    }
}
