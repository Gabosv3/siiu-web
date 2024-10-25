<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    //
    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar métodos específicos
        $this->middleware('can:categorias.index')->only('index');
        $this->middleware('can:categorias.create')->only('create', 'store');
        $this->middleware('can:categorias.edit')->only('edit', 'update');
        $this->middleware('can:categorias.destroy')->only('destroy');
        $this->middleware('can:categorias.restore')->only('restore');
    }

/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Muestra una lista de las categorías existentes en la base de datos.
     *
     * @return \Illuminate\Http\Response
/******  ee3d40ff-92df-4e82-85fc-5ed010535390  *******/
    public function index()
    {
        // Obtener todas las categorías de la base de datos con paginación
        $categories = Category::paginate();
        // Obtener categorías eliminadas
        $categoriesDeleted = Category::onlyTrashed()->get();
        // Retornar la vista 'categories.index' con las categorías y las eliminadas
        return view('categories.index', compact('categories', 'categoriesDeleted'))
            ->with('i', (request()->input('page', 1) - 1) * $categories->perPage());
    }

    public function create()
    {
        // Retornar la vista 'categories.create' para mostrar el formulario de creación
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'required|string|max:655',
            'image' => 'required|image|max:2048',
            'type' => 'required' // Validación para la imagen
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no debe superar los 255 caracteres',
            'name.unique' => 'La categoría ya existe',
            'description.required' => 'La descripción es obligatoria',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'description.max' => 'La descripción no debe superar los 655 caracteres',
            'image.required' => 'La imagen es obligatoria',
            'image.image' => 'La imagen debe ser una imagen',
            'image.max' => 'La imagen no debe superar los 2 MB',
        ]);

        // Inicializar el array de datos para la creación del modelo
        $data = $request->all();

        // Procesar y almacenar la imagen si fue cargada
        if ($request->hasFile('image')) {
            // Almacenar la imagen en el directorio 'categories' dentro de 'public'
            $imagePath = $request->file('image')->store('categories', 'public');

            // Obtener la URL completa de la imagen
            $data['image'] = Storage::url($imagePath);
        }

        // Crear una nueva categoría con los datos validados
        Category::create($data);

        // Redireccionar al índice de categorías con un mensaje de éxito
        return redirect()->route('categories.index')->with('status', 'Categoría Creado con éxito.');
    }

    public function show(Category $category)
    {
        // Retornar la vista 'categories.show' con la categoría específica
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        // Retornar la vista 'categories.edit' con la categoría específica
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Validar los datos del formulario, excluyendo el nombre actual de la categoría
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:65535',
            'image' => 'nullable|image|max:2048',
            'type' => 'required'
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no debe superar los 255 caracteres',
            'name.unique' => 'La categoría ya existe',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'description.max' => 'La descripción no debe superar los 65535 caracteres',
            'image.image' => 'La imagen debe ser una imagen',
            'image.max' => 'La imagen no debe superar los 2 MB',
        ]);

        // Procesar y almacenar la nueva imagen si fue cargada
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($category->image) {
                // Asegurarse de que la ruta sea correcta para la eliminación
                $previousImagePath = str_replace('/storage/', '', $category->image);
                if (Storage::disk('public')->exists($previousImagePath)) {
                    Storage::disk('public')->delete($previousImagePath);
                }
            }

            // Almacenar la nueva imagen
            $imagePath = $request->file('image')->store('categories', 'public');
            // Actualizar el atributo de imagen de la categoría con la URL correcta
            $category->image = Storage::url($imagePath);
        }

        // Actualizar la categoría con los datos validados
        $category->update($request->only('name', 'description'));

        // Guardar la ruta de la imagen actualizada si está establecida
        if ($request->hasFile('image')) {
            $category->save();
        }

        // Redireccionar al índice de categorías con un mensaje de éxito
        return redirect()->route('categories.index')->with('status', 'Categoría actualizada con éxito.');
    }
    public function destroy($id)
    {
        // Buscar la categoría por su ID y eliminarla
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return redirect()->route('categories.index')->with('status', 'Categoría Eliminada con éxito.');
        } else {
            return redirect()->back()->with('error', 'Categoría no encontrada.');
        }
    }

    public function restore($id)
    {
        // Buscar la categoría eliminada por su ID
        $category = Category::withTrashed()->find($id);

        if ($category) {
            // Restaurar la categoría eliminada
            $category->restore();
            return redirect()->route('categories.index')->with('status', 'Categoría Restaurado con éxito.');
        } else {
            return redirect()->route('categories.index')->with('error', 'Categoría no encontrada.');
        }
    }

    public function categoryViews()
    {
        // Obtener todas las categorías de la base de datos con paginación
        $categories = Category::where('type', 'Equipo')->paginate(10);
        // Retornar la vista 'inventory.index' con las categorías
        return view('inventories.index', compact('categories'))
            ->with('i', (request()->input('page', 1) - 1) * $categories->perPage());
    }

    public function categoryViewsForSupply()
    {
        // Obtener todas las categorías de la base de datos con paginación
        $categories = Category::where('type', 'Insumo')->paginate(10);
        // Retornar la vista 'inventory.index' con las categorías
        return view('inventories.supplies.searchforcategories', compact('categories'))
            ->with('i', (request()->input('page', 1) - 1) * $categories->perPage());
    }
}
