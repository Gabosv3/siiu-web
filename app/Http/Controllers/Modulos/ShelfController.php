<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Hardware;
use App\Models\Position;
use App\Models\Shelf;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    //
    public function index()
    {
        // Obtener estantes con sus posiciones y hardware relacionado
        $shelves = Shelf::with('positions.hardware')->get();

        // Obtener todos los hardware para la vista
        $hardwares = Hardware::all();

        return view('inventories.shelves.shelves', compact('shelves', 'hardwares')); // Pasar hardwares a la vista
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Crear un nuevo estante
        $shelf = Shelf::create([
            'name' => $request->name,
        ]);

        // Devolver una respuesta JSON
        return response()->json(['shelf' => $shelf], 201);
    }

    public function storePosition(Request $request)
    {
        $validatedData = $request->validate([
            'shelf_id' => 'required|exists:shelves,id',
            'estado' => 'required|in:empty,occupied',
        ]);

        // Crear la nueva posición
        Position::create([
            'shelf_id' => $request->input('shelf_id'),
            'estado' => $request->input('estado'),
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Posición creada con éxito.');
    }

    public function assignHardwareToPosition(Request $request)
    {
        $position = Position::find($request->position_id);
        $hardware = Hardware::find($request->hardware_id);

        // Asignar hardware a la posición
        $position->hardware_id = $hardware->id;
        $position->estado = 'occupied';
        $position->save();

        return redirect()->back()->with('success', 'Hardware asignado con éxito.');
    }
}
