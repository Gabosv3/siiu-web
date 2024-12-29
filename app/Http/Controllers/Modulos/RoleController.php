<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{
    // Constructor para aplicar middleware a ciertos métodos
    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar los métodos específicos
        $this->middleware('can:role.index')->only('index');
        $this->middleware('can:role.create')->only('create', 'store');
        $this->middleware('can:role.edit')->only('edit', 'update');
        $this->middleware('can:role.destroy')->only('destroy');
        $this->middleware('can:role.restore')->only('restore');
    }

    // Método para listar roles y mostrar la vista de índice
    public function index()
    {
        // Obtener roles con paginación
        $roles = Role::paginate();
        // Obtener roles eliminados
        $rolesDelets = Role::onlyTrashed()->get();
        // Obtener todos los permisos
        $permissions = Permission::all();

        // Retornar la vista 'role.index' con las variables necesarias
        return view('role.index', compact('roles', 'permissions', 'rolesDelets'))
            ->with('i', (request()->input('page', 1) - 1) * $roles->perPage());
    }

    // Método para mostrar la vista de creación de un nuevo rol
    public function create()
    {
        $role = new Role();
        return view('role.create', compact('role'));
    }

    // Método para almacenar un nuevo rol en la base de datos
    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|unique:roles,name', // El campo 'name' es requerido y debe ser único en la tabla 'roles'
        ], [
            'name.required' => 'El Role es requerido', // Mensaje de error si el campo 'name' está vacío
            'name.unique' => 'El Role ya ha sido usado', // Mensaje de error si el campo 'name' ya existe en la tabla 'roles'
        ]);

        // Creación del nuevo rol
        Role::create($request->all());

        // Redireccionamiento con mensaje de éxito
        return redirect()->route('role.index')->with('agregado', 'SI');
    }

    // Método para mostrar los detalles de un rol específico
    public function show($id)
    {
        $role = Role::find($id);
        return view('role.show', compact('role'));
    }

    // Método para mostrar la vista de edición de un rol
    public function edit($id)
    {
        // Buscar el rol por su ID
        $role = Role::findOrFail($id);

        // Obtener todos los permisos y agruparlos por el campo 'group' (o cualquier otro campo que desees)
        $permissionsGrouped = Permission::all()->groupBy('group');

        // Obtener los IDs de los permisos asignados al rol
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // Retornar la vista 'role.edit' con las variables necesarias
        return view('role.edit', compact('role', 'permissionsGrouped', 'rolePermissions'));
    }

    // Método para actualizar un rol en la base de datos
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id, // El campo 'name' es requerido y debe ser único en la tabla 'roles', excluyendo el rol actual
        ], [
            'name.required' => 'El Role es requerido', // Mensaje de error si el campo 'name' está vacío
            'name.unique' => 'El Role ya ha sido usado', // Mensaje de error si el campo 'name' ya existe en la tabla 'roles'
        ]);

        // Buscar el rol por su ID y actualizar su nombre
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        // Sincronizar permisos al rol
        $role->syncPermissions($request->permissions);

        // Redireccionamiento con mensaje de éxito
        return redirect()->route('role.index')->with('Actualizado', 'SI');
    }

    public function updateName(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ], [
            'name.required' => 'El Role es requerido',
            'name.unique' => 'El Role ya ha sido usado',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        return response()->json(['success' => true]);
    }

    public function updatePermission(Request $request, $id)
    {
        $request->validate([
            'permission_id' => 'nullable|exists:permissions,id',
            'group' => 'nullable|string',
            'is_checked' => 'required|boolean',
        ], [
            'permission_id.exists' => 'El permiso seleccionado no existe.',
            'group.string' => 'El campo "group" debe ser una cadena de texto.',
            'is_checked.boolean' => 'El campo "is_checked" debe ser un booleano.',
            
        ]);

        $role = Role::findOrFail($id);

        if ($request->permission_id) {
            if ($request->is_checked) {
                $role->givePermissionTo($request->permission_id);
            } else {
                $role->revokePermissionTo($request->permission_id);
            }
        }

        return response()->json(['success' => true]);
    }

    public function clone(Request $request, $roleId)
    {
        // Encontrar el rol original
        $role = Role::findOrFail($roleId);
    
        // Clonar el rol
        $newRole = $role->replicate();
        $newRole->name = $request->input('name');  // Asignar el nuevo nombre
        $newRole->save();
    
        // Clonar los permisos asociados
        $rolePermissions = $role->permissions;
        $newRole->permissions()->sync($rolePermissions);
    
        return response()->json([
            'message' => 'Rol clonado con éxito',
            'role' => $newRole
        ]);
    }

    // Método para eliminar un rol
    public function destroy($id)
    {
        // Buscar el rol por su ID y eliminarlo
        if (Role::find($id)->delete()) {
            return redirect()->back()->with('eliminado', 'SI');
        } else {
            return redirect()->back()->with('eliminado', 'NO');
        }
    }

    public function restore($id)
    {
        // Buscar el rol eliminado por su ID
        $role = Role::withTrashed()->find($id);

        if ($role) {
            // Restaurar el rol eliminado
            $role->restore();
            return redirect()->route('role.index')->with('Restaurado', 'SI');
        } else {
            return redirect()->route('role.index')->with('Restaurado', 'NO');
        }
    }
}
