<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
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
}
