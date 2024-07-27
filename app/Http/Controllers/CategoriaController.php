<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar los métodos específicos
        $this->middleware('can:categorias.index')->only('index');
        $this->middleware('can:categorias.create')->only('create', 'store');
        $this->middleware('can:categorias.edit')->only('edit', 'update');
        $this->middleware('can:categorias.destroy')->only('destroy');
        $this->middleware('can:categorias.restore')->only('restore');
    }

    public function index()
    {
        // Obtener todas las categorías de la base de datos
        $categorias = Categoria::paginate();
        // Obtener Categorias eliminados
        $categoriasDelets = Categoria::onlyTrashed()->get();
        // Retornar la vista 'categorias.index' con las categorías
        return view('categorias.index', compact('categorias','categoriasDelets'))
        ->with('i', (request()->input('page', 1) - 1) * $categorias->perPage());
    }

    public function create()
    {
        // Retornar la vista 'categorias.create' para mostrar el formulario de creación
        return view('categorias.create');
    }


    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
        ]);

        // Crear una nueva categoría con los datos validados
        Categoria::create($request->all());

        // Redireccionar al índice de categorías con un mensaje de éxito
        return redirect()->route('categorias.index')->with('agregado', 'SI');
    }

    public function show(Categoria $categoria)
    {
        // Retornar la vista 'categorias.show' con la categoría específica
        return view('categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria)
    {
        // Retornar la vista 'categorias.edit' con la categoría específica
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        // Validar los datos del formulario, excluyendo el nombre actual de la categoría
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
        ]);

        // Actualizar la categoría con los datos validados
        $categoria->update($request->all());

        // Redireccionar al índice de categorías con un mensaje de éxito
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy($id)
    {
       // Buscar la categoria por su ID y eliminarlo
       if (categoria::find($id)->delete()) {
        return redirect()->back()->with('eliminado', 'SI');
    } else {
        return redirect()->back()->with('eliminado', 'NO');
    }
    }

    public function restore($id)
    {
        // Buscar la categoria eliminado por su ID
        $categoria = categoria::withTrashed()->find($id);

        if ($categoria) {
            // Restaurar la categoria eliminado
            $categoria->restore();
            return redirect()->route('categorias.index')->with('Restaurado', 'SI');
        } else {
            return redirect()->route('categorias.index')->with('Restaurado', 'NO');
        }
    }
}
