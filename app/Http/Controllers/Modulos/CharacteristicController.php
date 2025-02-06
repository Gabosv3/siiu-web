<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Characteristic;
use App\Models\Models;
use Illuminate\Http\Request;

class CharacteristicController extends Controller
{
    //
    public function index()
    {
        $models = Models::all();
        return view('inventories.models.index', compact('models'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
    ]);

    $characteristic = Characteristic::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
    ]);

    return response()->json($characteristic, 201); // Devolver los datos de la nueva característica
}


}
