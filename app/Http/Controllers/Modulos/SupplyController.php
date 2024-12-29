<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Models;
use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    // Método para mostrar la lista de insumos
    public function index()
    {
        $supplies = Supply::with('category')->paginate(10);
        return view('inventories.supplies.index', compact('supplies'));
    }

    // Método para mostrar el formulario de creación
    public function create(Request $request)
    {
        $categoria = Category::find($request->input('category_id'));
        $fabricantes = Manufacturer::where('type', 'Insumo')->get();
        $modelos = Models::all();
        
        
        return view('inventories.supplies.create', [
            'categoria' => $categoria,
            'fabricantes' => $fabricantes,
            'modelos' => $modelos

        ]);
    }

    // Método para almacenar un nuevo insumo
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ],
        [
            'name.required' => 'El nombre es obligatorio',
            'category_id.required' => 'La categoría es obligatoria',
            'quantity.required' => 'La cantidad es obligatoria',
            'unit.required' => 'El unidad es obligatorio',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser "active" o "inactive"',
            'category_id.exists' => 'La categoría no existe',
            'quantity.min' => 'La cantidad debe ser mayor o igual a 1',
            'unit.max' => 'La unidad no debe superar los 50 caracteres',
            'name.max' => 'El nombre no debe superar los 255 caracteres',
            'description.max' => 'La descripción no debe superar los 65535 caracteres',
            'status.in' => 'El estado debe ser "active" o "inactive"',
            'status.in' => 'El estado debe ser "active" o "inactive"',
            'category_id.exists' => 'La categoría no existe',
        ]);

        Supply::create($request->all());

        return redirect()->route('supplies.index')->with('success', 'Supply created successfully.');
    }

    // Método para eliminar un insumo
    public function destroy($id)
    {
        $supply = Supply::findOrFail($id);
        $supply->delete();

        return redirect()->route('supplies.index')->with('success', 'Supply deleted successfully.');
    }
}
