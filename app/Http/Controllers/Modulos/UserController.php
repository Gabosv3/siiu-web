<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use App\Models\Departament;
use App\Models\User;
use App\Models\personal_information;
use App\Models\Technician;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    // Constructor para aplicar middleware a ciertos métodos
    public function __construct()
    {
        // Middleware para verificar permisos antes de ejecutar los métodos específicos
        $this->middleware('can:user.index')->only('index');
        $this->middleware('can:user.create')->only('create', 'store');
        $this->middleware('can:user.edit')->only('edit', 'update');
        $this->middleware('can:user.destroy')->only('destroy');
        $this->middleware('can:user.restore')->only('restore');
    }

    // Método para listar usuarios y mostrar la vista de índice
    public function index()
    {
        // Obtener usuarios con paginación
        $users = User::paginate(10); // Incluye los eliminados si es necesario

        // Obtener usuarios eliminados
        $deletedUsers = User::onlyTrashed()->get();

        // Obtener todos los departamentos
        $departamentos = Departament::all();

        // Obtener técnicos disponibles
        $technicians = Technician::where('available', true)->with('user')->get();

        // Obtener técnicos desactivados
        $deletedTechnicians = Technician::onlyTrashed()->get();

        // Obtener todos los roles excepto "SuperAdmin"
        $roles = Role::where('name', '!=', 'SuperAdmin')->get();

        // Retornar la vista 'user.index' con las variables necesarias
        return view('user.index', compact('users', 'departamentos', 'deletedUsers', 'technicians', 'deletedTechnicians', 'roles'));
    }

    // Método para mostrar la vista de creación de un nuevo usuario
    public function create()
    {
        $user = new User();
        return view('user.create', compact('user'));
    }

    // Método para almacenar un nuevo usuario en la base de datos
    public function store(Request $request)
{
    // Validación de los datos
    $request->validate([
        'email' => 'required|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'name' => 'required',
        'departament_id' => 'required|exists:departaments,id',
        'role_id' => 'required', // Validación básica
    ], [
        'email.required' => 'El correo es requerido',
        'email.unique' => 'El correo ya ha sido usado',
        'password.required' => 'La contraseña es requerida',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.confirmed' => 'Las contraseñas no coinciden',
        'name.required' => 'El nombre es requerido',
        'departament_id.required' => 'El departamento es requerido',
        'departament_id.exists' => 'El departamento seleccionado no es válido',
        'role_id.required' => 'Debe seleccionar al menos un rol',
    ]);

    // Si role_id es un solo valor, conviértelo en un array
    $roles = is_array($request->role_id) ? $request->role_id : [$request->role_id];

    // Validación adicional para asegurar que todos los roles existen
    $request->validate([
        'role_id.*' => 'exists:roles,id',
    ], [
        'role_id.*.exists' => 'Uno o más roles seleccionados no son válidos',
    ]);

    // Crear un nuevo usuario
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'departament_id' => $request->departament_id,
    ]);

    // Asignar roles seleccionados
    $user->roles()->sync($roles);

    // Redireccionamiento con mensaje de éxito
    return redirect()->route('user.index')->with('agregado', 'SI');
}

    // Método para mostrar los detalles de un usuario específico
    public function show($id)
    {
        $user = User::with('personalInformation')->find($id);
        return view('user.show', compact('user'));
    }

    // Método para mostrar la vista de edición de un usuario
    public function editUser($id)
    {
        // Buscar el usuario por su ID
        $user = User::with('personalInformation')->find($id);
        // Obtener todos los departamentos
        $departamentos = Departament::all();
        // Obtener todos los roles
        $roles = Role::all();
        // Obtener los IDs de los roles asignados al usuario
        $userRoles = $user->roles->pluck('id')->toArray();

        // Retornar los datos necesarios para la vista
        return [
            'user' => $user,
            'departamentos' => $departamentos,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ];
    }

    // Método para mostrar la vista de edición de un usuario
    public function edit($id)
    {
        // Llamar al método editUser y obtener los datos
        $data = $this->editUser($id);

        // Pasar los datos a la vista
        return view('user.edit', $data);
    }

    public function one_Edit($id)
    {
        $data = $this->editUser($id);

        // Pasar los datos a la vista
        return view('authenticated.user_edit', $data);
    }

    public function updateUser(Request $request, User $user, $validateRoles = true, $validateDepartment = true)
    {
        // Definir las reglas de validación
        $rules = [
            'email' => 'required|unique:users,email,' . $user->id,
            'name' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'dui' => 'required|unique:personal_informations,dui,' . ($user->personalInformation ? $user->personalInformation->id : 'NULL') . ',user_id',
            'phone' => 'required|unique:personal_informations,phone,' . ($user->personalInformation ? $user->personalInformation->id : 'NULL') . ',user_id',
        ];

        // Agregar la regla de validación para departament_id si es necesario
        if ($validateDepartment) {
            $rules['departament_id'] = 'required|exists:departaments,id';
        }

        // Mensajes de validación personalizados
        $messages = [
            'email.required' => 'El correo es requerido',
            'email.unique' => 'El correo ya ha sido usado',
            'name.required' => 'El nombre es requerido',
            'departament_id.required' => 'El departamento es requerido',
            'departament_id.exists' => 'El departamento no es válido',
            'last_name.required' => 'Los apellidos son requeridos',
            'first_name.required' => 'Los nombres son requeridos',
            'birth_date.required' => 'La fecha de nacimiento es requerida',
            'birth_date.date' => 'La fecha de nacimiento no es válida',
            'gender.required' => 'El género es requerido',
            'dui.required' => 'El DUI es requerido',
            'dui.unique' => 'El DUI ya ha sido usado',
            'phone.required' => 'El teléfono es requerido',
            'phone.unique' => 'El teléfono ya ha sido usado',
        ];

        // Validar los datos
        $request->validate($rules, $messages);

        // Preparar los datos del usuario para actualizar
        $userData = $request->only('name', 'email');
        if ($validateDepartment) {
            $userData['departament_id'] = $request->departament_id;
        }
        $user->update($userData);

        // Sincronizar roles al usuario si la bandera validateRoles es verdadera
        if ($validateRoles) {
            $user->syncRoles($request->roles);
        }

        // Preparar los datos de información personal para actualizar
        $informacionPersonalData = $request->only('last_name', 'first_name', 'birth_date', 'gender', 'dui', 'phone');
        $informacionPersonal = $user->personalInformation;
        if ($informacionPersonal) {
            $informacionPersonal->update($informacionPersonalData);
        } else {
            $informacionPersonalData['user_id'] = $user->id;
            personal_information::create($informacionPersonalData);
        }
    }

    public function update(Request $request, User $user)
    {
        $this->updateUser($request, $user, true, true); // Valida y actualiza roles y departamento
        return redirect()->back()->with('Actualizado', 'SI');
    }

    public function oneUpdate(Request $request, User $user)
    {
        $this->updateUser($request, $user, false, false); // No valida ni actualiza roles ni departamento
        return redirect()->back()->with('status', 'Usuario actualizado');
    }

    // Método para eliminar un usuario
    public function destroy($id)
    {
        // Buscar el usuario por su ID y eliminarlo
        if (User::find($id)->delete()) {
            return redirect()->back()->with('eliminado', 'SI');
        } else {
            return redirect()->back()->with('eliminado', 'NO');
        }
    }

    // Método para restaurar un usuario eliminado
    public function restore($id)
    {
        // Buscar el usuario eliminado por su ID
        $user = User::withTrashed()->find($id);

        if ($user) {
            // Restaurar el usuario eliminado
            $user->restore();
            return redirect()->back()->with('Restaurado', 'SI');
        } else {
            return redirect()->back()->with('Restaurado', 'NO');
        }
    }
}
