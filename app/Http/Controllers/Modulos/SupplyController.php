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
        $supplies = Supply::with('category')->get();
        $deletedSupplies = Supply::onlyTrashed()->with('category')->get();
        return view('inventories.supplies.index', compact('supplies', 'deletedSupplies'));
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

        return redirect()->route('supplies.index')->with('success', 'Insumo creado exitosamente.');
    }

    public function edit(Supply $supply)
    {
        $categoria = Category::find($supply->category_id);
        $fabricantes = Manufacturer::where('type', 'Insumo')->get();
        $modelos = Models::all();
        $insumo  = $supply;
        return view('inventories.supplies.edit', compact( 'categoria','insumo', 'fabricantes', 'modelos'));
    }

    public function update(Request $request, Supply $supply)
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
            'category_id.exists' => 'La categoría no existe',
        ]);

        $supply->update($request->all());

        return redirect()->route('supplies.index')->with('success', 'Insumo actualizado exitosamente.');
    }

    public function show($id)
    {
        $supply = Supply::findOrFail($id);
        return view('inventories.supplies.show', compact('supply'));
    }

    


    // Método para eliminar un insumo
    public function destroy($id)
    {
        $supply = Supply::findOrFail($id);
        $supply->delete();

        return redirect()->route('supplies.index')->with('success', 'Insumo eliminado exitosamente.');
    }

    public function restore($id)
    {
        $supply = Supply::withTrashed()->findOrFail($id);
        $supply->restore();

        return redirect()->route('supplies.index')->with('success', 'Insumo restaurado exitosamente.');
    }
}
