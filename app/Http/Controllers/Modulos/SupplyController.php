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
