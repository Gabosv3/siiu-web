<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // Obtener todas las categorías de la base de datos con paginación
        $categorias = Categoria::paginate();
        // Obtener categorías eliminadas
        $categoriasDelets = Categoria::onlyTrashed()->get();
        // Retornar la vista 'categorias.index' con las categorías y las eliminadas
        return view('categorias.index', compact('categorias', 'categoriasDelets'))
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
            'descripcion' => 'nullable|string|max:65535',
            'imagen' => 'nullable|image|max:2048', // Validación para la imagen
        ]);

        // Inicializar el array de datos para la creación del modelo
        $data = $request->all();

        // Procesar y almacenar la imagen si fue cargada
        if ($request->hasFile('imagen')) {
            // Almacenar la imagen en el directorio 'categorias' dentro de 'public'
            $imagePath = $request->file('imagen')->store('categorias', 'public');

            // Obtener la URL completa de la imagen
            $imageUrl = Storage::url($imagePath);

            // Añadir la URL de la imagen a los datos del formulario
            $data['imagen'] = $imageUrl;
        }

        // Crear una nueva categoría con los datos validados
        Categoria::create($data);

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
            'descripcion' => 'nullable|string|max:65535',
            'imagen' => 'nullable|image|max:2048', // Validación para la imagen
        ]);

        // Procesar y almacenar la nueva imagen si fue cargada
        if ($request->hasFile('imagen')) {
            // Elimina la imagen anterior si existe
            if ($categoria->imagen) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $imagePath = $request->file('imagen')->store('categorias', 'public');
            $request->merge(['imagen' => $imagePath]);
        }

        // Actualizar la categoría con los datos validados
        $categoria->update($request->all());

        // Redireccionar al índice de categorías con un mensaje de éxito
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy($id)
    {
        // Buscar la categoría por su ID y eliminarla
        $categoria = Categoria::find($id);

        if ($categoria) {
            $categoria->delete();
            return redirect()->back()->with('eliminado', 'SI');
        } else {
            return redirect()->back()->with('eliminado', 'NO');
        }
    }

    public function restore($id)
    {
        // Buscar la categoría eliminada por su ID
        $categoria = Categoria::withTrashed()->find($id);

        if ($categoria) {
            // Restaurar la categoría eliminada
            $categoria->restore();
            return redirect()->route('categorias.index')->with('Restaurado', 'SI');
        } else {
            return redirect()->route('categorias.index')->with('Restaurado', 'NO');
        }
    }
    public function Categoriasvistas()
    {
        // Obtener todas las categorías de la base de datos con paginación
        $categorias = Categoria::paginate(10);
        // Retornar la vista 'categorias.index' con las categorías y las eliminadas
        return view('inventario.index', compact('categorias'))
            ->with('i', (request()->input('page', 1) - 1) * $categorias->perPage());
    }
}
