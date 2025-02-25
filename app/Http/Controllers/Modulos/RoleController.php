<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{

    /**
     * Constructor del controlador.
     *
     * Establece middleware para controlar los permisos de acceso a los métodos
     * del controlador. Los middleware se aplican a los métodos según se indica
     * a continuación:
     *
     * - index: can:role.index
     * - create y store: can:role.create
     * - edit y update: can:role.edit
     * - destroy: can:role.destroy
     * - restore: can:role.restore
     */

    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar los métodos específicos
        $this->middleware('can:role.index')->only('index');
        $this->middleware('can:role.create')->only('create', 'store');
        $this->middleware('can:role.edit')->only('edit', 'update');
        $this->middleware('can:role.destroy')->only('destroy');
        $this->middleware('can:role.restore')->only('restore');
    }


    /**
     * Muestra una lista de los roles registrados en la aplicación,
     * incluyendo los eliminados.
     *
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Muestra la vista de creación de un nuevo rol.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role();
        return view('role.create', compact('role'));
    }


    /**
     * Crea un nuevo rol en la base de datos.
     *
     * Valida los datos del formulario y crea un nuevo rol con los datos
     * proporcionados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Muestra la vista de detalles de un rol en particular.
     *
     * Obtiene el rol por su ID y lo pasa a la vista 'role.show' para mostrar
     * sus detalles.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        return view('role.show', compact('role'));
    }


    /**
     * Muestra la vista de edición de un rol en particular.
     *
     * Obtiene el rol por su ID y lo pasa a la vista 'role.edit' para mostrar
     * sus detalles y permitir la edición de sus permisos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Actualiza un rol existente en la base de datos.
     *
     * Valida los datos del formulario y actualiza el nombre
     * del rol y sus permisos asociados.
     * En caso de éxito, redirige a la vista de roles con un mensaje de éxito.
     * Si ocurre un error, redirige con un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del rol a actualizar.
     * @param int $id El ID del rol que se va a actualizar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección a la vista de roles.
     */
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

    /**
     * Actualiza el nombre de un rol existente en la base de datos.
     *
     * Valida el nombre del rol y actualiza el nombre
     * del rol en la base de datos.
     * En caso de éxito, devuelve una respuesta JSON con un mensaje de éxito.
     * Si ocurre un error, devuelve una respuesta JSON con un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene el nuevo nombre del rol.
     * @param int $id El ID del rol que se va a actualizar.
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con el resultado de la operación.
     */
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

    
    /**
     * Actualiza un permiso asociado a un rol existente en la base de datos.
     *
     * Valida los datos del request y actualiza el permiso
     * asociado al rol en la base de datos.
     * En caso de éxito, devuelve una respuesta JSON con un mensaje de éxito.
     * Si ocurre un error, devuelve una respuesta JSON con un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del permiso a actualizar.
     * @param int $id El ID del rol que se va a actualizar.
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con el resultado de la operación.
     */
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

    /**
     * Clona un rol existente en la base de datos.
     *
     * El método encuentra el rol original mediante su ID, clona el rol
     * y asigna el nuevo nombre proporcionado en la solicitud. Luego,
     * clona los permisos asociados y los asigna al nuevo rol. El
     * método devuelve una respuesta JSON con un mensaje de éxito y
     * el nuevo rol.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que
     *        contiene el nombre del nuevo rol.
     * @param int $roleId El ID del rol que se va a clonar.
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con el
     *         resultado de la operación.
     */
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


    /**
     * Elimina un rol de la base de datos.
     *
     * El método busca el rol por su ID y lo elimina. Si el rol existe,
     * se redirige a la lista de roles con un mensaje de éxito. De lo
     * contrario, se redirige con un mensaje de error.
     *
     * @param int $id El ID del rol a eliminar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección
     *         con el resultado de la operación.
     */
    public function destroy($id)
    {
        // Buscar el rol por su ID y eliminarlo
        if (Role::find($id)->delete()) {
            return redirect()->back()->with('eliminado', 'SI');
        } else {
            return redirect()->back()->with('eliminado', 'NO');
        }
    }

    /**
     * Restaura un rol eliminado de la base de datos.
     *
     * Busca el rol eliminado por su ID y lo restaura. Si el rol existe,
     * se redirige a la lista de roles con un mensaje de éxito. De lo
     * contrario, se redirige con un mensaje de error.
     *
     * @param int $id El ID del rol a restaurar.
     * @return \Illuminate\Http\RedirectResponse La respuesta de redirección
     *         con el resultado de la operación.
     */
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
