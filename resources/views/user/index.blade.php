@extends('layouts.user_type.auth')

@section('content')
<!-- Contenedor  -->
<div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
    <div class="row">
        <!-- Contenedor para el titulo del pagina   -->
        <div class="col-md-4 text-center mx-auto">
            <img src="https://www.pavilionweb.com/wp-content/uploads/2017/03/man-300x300.png" width="200px">
            <h3 class="center">USUARIOS</h3>
        </div>
        <!-- Contenedor para el boton de crear usuario  -->
        <div class="col-md-4 d-flex justify-content-center align-items-center">
            @can('user.create')
            <div>
                <button type="button" class="btn btn-rounded btn-md bg-gradient-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="btn-abrir-modal-crear-usuario">Crear Usuario</button>
            </div>
            @endcan
        </div>
    </div>
</div>
<!-- Contenedor para el Modal de crear usuario -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <!-- Título del modal -->
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Crear Usuario</h1>
                <!-- Botón de cerrar el modal -->
                <button type="button" class="btn-close bg-gradient-2" data-bs-dismiss="modal" aria-label="Close" id="btn-cerrar-modal-crear-usuario"></button>
            </div>
            <!-- Formulario para crear Usuario -->
            <form class="row g-3 text-dark text-center" action="{{ route('user.store') }}" method="POST" onsubmit="return validateForm()">
                @csrf
                <div class="modal-body">
                    <!-- Campo para el nombre de usuario -->
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Usuario</label>
                        <input name="name" type="text" class="border-dark form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp" required minlength="8" value="{{ old('name', $user->name ?? '') }}">
                        @error('name')
                        <!-- Mensaje de error para el campo nombre si existe -->
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para el correo electrónico -->
                    <div class="col-md-12 mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input name="email" type="email" class="border-dark form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" required value="{{ old('email', $user->email ?? '') }}">
                        @error('email')
                        <!-- Mensaje de error para el campo correo electrónico si existe -->
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Campo para la contraseña -->
                    <div class="col-md-12 mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input name="password" type="password" class="border-dark form-control @error('password') is-invalid @enderror" id="password" required>
                        <!-- Checkbox para mostrar/ocultar la contraseña -->
                        <input type="checkbox" onclick="togglePassword('password')"> Mostrar Contraseña
                        @error('password')
                        <!-- Mensaje de error para el campo contraseña si existe -->
                        <small class="text-danger mt-1">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                    <!-- Campo para confirmar la contraseña -->
                    <div class="col-md-12 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input name="password_confirmation" type="password" class="border-dark form-control" id="password_confirmation" required>
                        <!-- Checkbox para mostrar/ocultar la contraseña de confirmación -->
                        <input type="checkbox" onclick="togglePassword('password_confirmation')"> Mostrar Contraseña
                    </div>
                    <!-- Campo para asignar un departamento -->
                    <div class="form-group">
                        <label for="departamento_id" class="form-label">Departamento:</label>
                        <select class="form-control @error('departamento_id') is-invalid @enderror" id="departamento_id" name="departamento_id" required>
                            <!-- Opción predeterminada -->
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                            <!-- Opciones de departamentos cargadas dinámicamente -->
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                        @error('departamento_id')
                        <!-- Mensaje de error para el campo departamento si existe -->
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- Pie del modal -->
                <div class="modal-footer">
                    <!-- Botón para cerrar el modal -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-cerrar-modal-guardar-usuario">Cerrar</button>
                    <!-- Botón para enviar el formulario y registrar el usuario -->
                    <button type="submit" class="btn bg-gradient-2" style="--bs-btn-opacity: .5; " id="btn-crear-usuario">REGISTRAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- menu de navegacion de formularios  -->
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Usuarios</button>
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Eliminados</button>
    </div>
</nav>
<!-- contenedor principal de la tabla de usuarios  -->
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3 ">
            <!-- Tabla principal -->
            <table id="Principal" class="table align-items-center mb-0 text-center " style="width:100% ">
                <!-- Encabezado de la tabla -->
                <thead class="bg-gradient-2 text-center">
                    <tr>
                        <th>#</th> <!-- Número de fila -->
                        <th>USUARIO</th> <!-- Nombre del usuario -->
                        <th>CORREO</th> <!-- Correo electrónico del usuario -->
                        <th>DEPARTAMENTO</th> <!-- Departamento del usuario -->
                        <th>2FA</th> <!-- Estado de autenticación de dos factores -->
                        <th>ACCIONES</th> <!-- Acciones disponibles para cada usuario -->
                    </tr>
                </thead>
                <!-- Cuerpo de la tabla -->
                <tbody>
                    @foreach ($users as $key => $user)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th> <!-- Número de fila -->
                        <td>{{ $user->name }}</td> <!-- Nombre del usuario -->
                        <td>{{ $user->email }}</td> <!-- Correo electrónico del usuario -->
                        <td>
                            @if(isset($user->departamento))
                            {{ $user->departamento->nombre }} <!-- Nombre del departamento -->
                            @else
                            <span>Sin departamento</span> <!-- Mensaje si el usuario no tiene departamento -->
                            @endif
                        </td>
                        <td>
                            @if(isset($user->loginSecurity) && $user->loginSecurity->google2fa_enable)
                            <span class="badge bg-success">Activado</span> <!-- Indicador de 2FA activado -->
                            @else
                            <span class="badge bg-danger">Desactivado</span> <!-- Indicador de 2FA desactivado -->
                            @endif
                        </td>
                        <td>
                            <!-- Botón para redireccionar a ver los usuarios -->
                            @can('user.index')
                            <a  title="Ver usuario" id="btn-ver-usuario" href="{{ route('user.show', $user->id) }}" class="btn btn-cyan-800 mb-3"><i class='bx bxs-show'></i></a>
                            @endcan
                            <!-- Botón para redireccionar a editar los usuarios -->
                            @can('user.edit')
                            <a  title="Editar usuario" id="btn-editar-usuario" href="{{ route('user.edit', $user->id) }}" class="btn btn-green-600 mb-3"><i class='bx bxs-edit-alt'></i></a>
                            @endcan
                            <!-- Formulario para eliminar el usuario -->
                            @can('user.destroy')
                            <form method="POST" class="formulario-eliminar" style="display:inline;" action="{{ route('user.destroy', $user->id) }}">
                                @method('DELETE')
                                @csrf
                                <button  title="Eliminar usuario" id="btn-eliminar-usuario" class="btn btn-red-800" @if ($user->id === Auth::user()->id) disabled @endif><i class='bx bxs-trash'></i></button>
                            </form>
                            @endcan

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- contenedor para vista de la tabla restaurar usuario -->
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3 ">
            <!-- tabla restaurar usuario -->
            <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100% ">
                <!-- Cabezera de la tabla restaurar usuario -->
                <thead class="bg-gradient-2 text-center">
                    <tr>
                        <th>#</th> <!-- Número de fila -->
                        <th>Nombre</th> <!-- Nombre del usuario -->
                        <th>Email</th> <!-- Correo electrónico del usuario -->
                        <th>Fecha Eliminación</th> <!-- Fecha en que el usuario fue eliminado -->
                        <th class="w-10">Restaurar</th> <!-- Acción para restaurar el usuario -->
                    </tr>
                </thead>
                <!-- Cuerpo de la tabla usuario -->
                <tbody>
                    @foreach ($usersDelets as $key => $user)
                    <tr>
                        <td class="text-center" scope="row">{{ $key + 1 }}</td> <!-- Número de fila -->
                        <td>{{ $user->name }}</td> <!-- Nombre del usuario -->
                        <td>{{ $user->email }}</td> <!-- Correo electrónico del usuario -->
                        <td>{{ $user->deleted_at }}</td> <!-- Fecha de eliminación -->
                        <td>
                            @can('user.restore')
                            <!-- Formulario para restaurar el usuario -->
                            <form action="{{ route('user.restore', $user->id) }}" class="formulario-restaurar" method="POST">
                                @csrf
                                @method('PUT')
                                <button id="btn-restaurar-usuario" class="btn btn-cyan-800 mb-3" type="submit">Restaurar</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('components.script-btn')

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>
<script src="{{ asset('assets/js/Usuarios/UserIndex.js') }}"></script>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Muestra un SweetAlert con un mensaje de error si hay errores
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Usuario no se pudo crear'
        }).then(() => {
            // Después de cerrar el SweetAlert, abre automáticamente el modal
            var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            modal.show();
        });
    });
</script>
@endif

@foreach (['agregado', 'eliminado', 'Actualizado', 'Restaurado'] as $sessionKey)
@if (session($sessionKey) == 'SI')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Muestra un SweetAlert de éxito si la sesión contiene un mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: '{{ ucfirst($sessionKey) }}',
            text: 'Usuario {{ strtolower($sessionKey) }} correctamente.'
        });
    });
</script>
@elseif (session($sessionKey) == 'NO')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Muestra un SweetAlert de error si la sesión contiene un mensaje de error
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Usuario no se pudo {{ strtolower($sessionKey) }}'
        });
    });
</script>
@endif
@endforeach

@endsection