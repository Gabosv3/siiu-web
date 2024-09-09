<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Fabricante;
use App\Models\Hardware;
use App\Models\Modelo;
use App\Models\Software;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class HardwareController extends Controller
{
    public function index(Request $request)
    {
        $viewType = $request->input('view', 'card'); // Obtiene el tipo de vista desde la consulta, por defecto es 'card'
        $categoriaId = $request->input('categoria_id'); // Obtiene el ID de la categoría si está presente

        // Obtiene todos los registros de hardware y aplica el filtro por categoría si se proporciona
        $hardwares = Hardware::when($categoriaId && $categoriaId != 'all', function ($query) use ($categoriaId) {
            return $query->where('categoria_id', $categoriaId);
        })->with(['categoria', 'fabricante', 'modelo', 'tags', 'sistemas'])->get();

        return view('inventario.hardware.index', compact('hardwares', 'viewType'));
    }

    public function create(Request $request)
{
    // Obtener las variables necesarias
    $fabricantes = Fabricante::all();
    $categorias = Categoria::all();
    $usuarios = User::all();
    $departamentos = Departamento::all();
    $modelos = Modelo::all();
    $tags = Tag::all();
    $sistemas = Software::all();

    // Obtener la categoría con base en el ID proporcionado en la solicitud (si existe)
    $categoria = Categoria::find($request->input('categoria_id'));

    // Verifica si se ha enviado un archivo CSV
    $csvData = null;
    if ($request->session()->has('csv_data')) {
        $csvData = $request->session()->get('csv_data');
    }

    return view('inventario.hardware.create', [
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
            'name' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'conflictos' => 'nullable|string',
            'estado' => 'required|string',
            'dueno_id' => 'nullable|exists:users,id',
            'ubicacion_id' => 'nullable|exists:departamentos,id',
            'codigo_de_inventario' => 'required|string|max:255',
            'fabricante_id' => 'nullable|exists:fabricantes,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'sistemas_asignados' => 'nullable|array',
            'sistemas_asignados.*' => 'exists:sistemas,id',
        ]);

        $hardware = Hardware::create($request->except(['tags', 'sistemas_asignados']));

        if ($request->has('tags')) {
            $hardware->tags()->sync($request->input('tags'));
        }

        if ($request->has('sistemas_asignados')) {
            $hardware->sistemas()->sync($request->input('sistemas_asignados'));
        }

        return redirect()->route('hardwares.index')->with('success', 'Hardware creado exitosamente.');
    }

    public function show(Hardware $hardware)
    {
        return view('inventario.hardware.show', compact('hardware'));
    }

    public function edit(Hardware $hardware)
    {
        $usuarios = User::all();
        $departamentos = Departamento::all();
        $categorias = Categoria::all();
        $fabricantes = Fabricante::all();
        $modelos = Modelo::all();
        $tags = Tag::all();
        $sistemas = Software::all();
        return view('inventario.hardware.edit', compact('hardware', 'categorias', 'fabricantes', 'modelos', 'tags', 'sistemas','usuarios','departamentos'));
    }

    public function update(Request $request, Hardware $hardware)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'conflictos' => 'nullable|string',
            'estado' => 'required|string',
            'dueno_id' => 'nullable|exists:users,id',
            'ubicacion_id' => 'nullable|exists:departamentos,id',
            'codigo_de_inventario' => 'required|string|max:255',
            'fabricante_id' => 'nullable|exists:fabricantes,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'sistemas_asignados' => 'nullable|array',
            'sistemas_asignados.*' => 'exists:sistemas,id',
        ]);

        $hardware->update($request->except(['tags', 'sistemas_asignados']));

        if ($request->has('tags')) {
            $hardware->tags()->sync($request->input('tags'));
        }

        if ($request->has('sistemas_asignados')) {
            $hardware->sistemas()->sync($request->input('sistemas_asignados'));
        }

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
