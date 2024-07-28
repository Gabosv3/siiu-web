<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departamento;
use App\Models\InformacionPersonal;
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
        $users = User::paginate();
        // Obtener usuarios eliminados
        $usersDelets = User::onlyTrashed()->get();
        // Obtener todos los departamentos
        $departamentos = Departamento::all();

        // Retornar la vista 'user.index' con las variables necesarias
        return view('user.index', compact('users', 'departamentos', 'usersDelets'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
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
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'name' => 'required',
            'departamento_id' => 'required|exists:departamentos,id',
        ], [
            'email.required' => 'El correo es requerido',
            'email.unique' => 'El correo ya ha sido usado',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener 8 caracteres',
            'password_confirmation.required' => 'La confirmación de la contraseña es requerida',
            'password.same' => 'Las contraseñas no coinciden',
            'name.required' => 'El nombre es requerido',
            'departamento_id.required' => 'El departamento es requerido',
            'departamento_id.exists' => 'El departamento seleccionado no es válido',
        ]);

        // Crear un nuevo usuario
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'departamento_id' => $request->departamento_id,
        ])->assignRole('Usuario');

        // Redireccionamiento con mensaje de éxito
        return redirect()->route('user.index')->with('agregado', 'SI');
    }

    // Método para mostrar los detalles de un usuario específico
    public function show($id)
    {
        $user = User::with('informacionPersonal')->find($id);
        return view('user.show', compact('user'));
    }

    // Método para mostrar la vista de edición de un usuario
    public function editar($id)
    {
        // Buscar el usuario por su ID
        $user = User::with('informacionPersonal')->find($id);
        // Obtener todos los departamentos
        $departamentos = Departamento::all();
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
        // Llamar al método editar y obtener los datos
        $data = $this->editar($id);

        // Pasar los datos a la vista
        return view('user.edit', $data);
    }
    public function one_edit($id)
    {
        $data = $this->editar($id);

        // Pasar los datos a la vista
        return view('Auth.user_edit', $data);
    }

    public function actualizar(Request $request, User $user, $validateRoles = true, $validateDepartamento = true)
    {
        // Definir las reglas de validación
        $rules = [
            'email' => 'required|unique:users,email,' . $user->id,
            'name' => 'required',
            'apellidos' => 'required',
            'nombres' => 'required',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required',
            'dui' => 'required|unique:informacion_personals,dui,' . ($user->informacionPersonal ? $user->informacionPersonal->id : 'NULL') . ',user_id',
            'telefono' => 'required|unique:informacion_personals,telefono,' . ($user->informacionPersonal ? $user->informacionPersonal->id : 'NULL') . ',user_id',
        ];

        // Agregar la regla de validación para departamento_id si es necesario
        if ($validateDepartamento) {
            $rules['departamento_id'] = 'required|exists:departamentos,id';
        }

        // Mensajes de validación personalizados
        $messages = [
            'email.required' => 'El correo es requerido',
            'email.unique' => 'El correo ya ha sido usado',
            'name.required' => 'El nombre es requerido',
            'departamento_id.required' => 'El departamento es requerido',
            'departamento_id.exists' => 'El departamento no es válido',
            'apellidos.required' => 'Los apellidos son requeridos',
            'nombres.required' => 'Los nombres son requeridos',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida',
            'genero.required' => 'El género es requerido',
            'dui.required' => 'El DUI es requerido',
            'dui.unique' => 'El DUI ya ha sido usado',
            'telefono.required' => 'El teléfono es requerido',
            'telefono.unique' => 'El teléfono ya ha sido usado',
        ];

        // Validar los datos
        $request->validate($rules, $messages);

        // Preparar los datos del usuario para actualizar
        $userData = $request->only('name', 'email');
        if ($validateDepartamento) {
            $userData['departamento_id'] = $request->departamento_id;
        }
        $user->update($userData);

        // Sincronizar roles al usuario si la bandera validateRoles es verdadera
        if ($validateRoles) {
            $user->syncRoles($request->roles);
        }

        // Preparar los datos de información personal para actualizar
        $informacionPersonalData = $request->only('apellidos', 'nombres', 'fecha_nacimiento', 'genero', 'dui', 'telefono');
        $informacionPersonal = $user->informacionPersonal;
        if ($informacionPersonal) {
            $informacionPersonal->update($informacionPersonalData);
        } else {
            $informacionPersonalData['user_id'] = $user->id;
            InformacionPersonal::create($informacionPersonalData);
        }
    }

    public function update(Request $request, User $user)
    {
        $this->actualizar($request, $user, true, true); // Valida y actualiza roles y departamento
        return redirect()->back()->with('Actualizado', 'SI');
    }

    public function one_update(Request $request, User $user)
    {
        $this->actualizar($request, $user, false, false); // No valida ni actualiza roles ni departamento
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
