@extends('layouts.user_type.auth')

@section('content')
<!-- Contenedor  -->
<div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
    <div class="row">
        <!-- Contenedor para el titulo del pagina   -->
        <div class="col-md-4 text-center mx-auto">
            <img src="https://www.pavilionweb.com/wp-content/uploads/2017/03/man-300x300.png" width="200px" alt="Imagen de usuario">
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
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Crear Usuario</h1>
            </div>
            <form class="row g-3 text-dark text-center" action="{{ route('user.store') }}" method="POST" onsubmit="return validateForm()">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Usuario</label>
                        <input name="name" type="text" class="border-dark form-control @error('name') is-invalid @enderror" id="name" required minlength="6" value="{{ old('name', $user->name ?? '') }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input name="email" type="email" class="border-dark form-control @error('email') is-invalid @enderror" id="email" required value="{{ old('email', $user->email ?? '') }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input name="password" type="password" class="border-dark form-control @error('password') is-invalid @enderror" id="password" required>
                        <input type="checkbox" onclick="togglePassword('password')"> <label for="text">mostrar contraseña</label>
                        @error('password')
                        <small class="text-danger mt-1"><strong>{{ $message }}</strong></small>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input name="password_confirmation" type="password" class="border-dark form-control" id="password_confirmation" required>
                        <input type="checkbox" onclick="togglePassword('password_confirmation')"> <label for="text">mostrar contraseña</label>
                    </div>
                    <div class="form-group">
                        <label for="departament_id" class="form-label">Departamento:</label>
                        <select class="form-control @error('departament_id') is-invalid @enderror js-select-departamento" id="departament_id" name="departament_id" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->name }}</option>
                            @endforeach
                        </select>
                        @error('departamento_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-cerrar-modal-guardar-usuario">Cerrar</button>
                    <button type="submit" class="btn bg-gradient-2" style="--bs-btn-opacity: .5;" id="btn-crear-usuario">REGISTRAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- menu de navegacion de formularios  -->
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Usuarios</button>
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Desactivados</button>
        <button class="nav-link" id="nav-technician-tab" data-bs-toggle="tab" data-bs-target="#nav-technicians" type="button" role="tab" aria-controls="nav-technicians" aria-selected="false">Técnicos</button>
        <button class="nav-link" id="nav-deletedTechnicians-tab" data-bs-toggle="tab" data-bs-target="#nav-deletedTechnicians" type="button" role="tab" aria-controls="nav-deletedTechnicians" aria-selected="false">Tecnicos Desactivados</button>
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
                            @if(isset($user->departament))
                            {{ $user->departament->name }} <!-- Nombre del departamento -->
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
                            <a title="Ver usuario" id="btn-ver-usuario" href="{{ route('user.show', $user->id) }}" class="btn btn-cyan-800 mb-3"><i class='bx bxs-show'></i></a>
                            @endcan
                            <!-- Botón para redireccionar a editar los usuarios -->
                            @can('user.edit')
                            <a title="Editar usuario" id="btn-editar-usuario" href="{{ route('user.edit', $user->id) }}" class="btn btn-green-600 mb-3"><i class='bx bxs-edit-alt'></i></a>
                            @endcan
                            <!-- Formulario para eliminar el usuario -->
                            @can('user.destroy')
                            <form method="POST" class="formulario-eliminar" style="display:inline;" action="{{ route('user.destroy', $user->id) }}">
                                @method('DELETE')
                                @csrf
                                <button title="Eliminar usuario" id="btn-eliminar-usuario" class="btn btn-red-800" @if ($user->id === Auth::user()->id) disabled @endif><i class='bx bxs-trash'></i></button>
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
                    @foreach ($deletedUsers as $key => $user)
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
    <!-- Técnicos -->
    <div class="tab-pane fade" id="nav-technicians" role="tabpanel" aria-labelledby="nav-technician-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <div class="d-flex justify-content-end">
                <a href="{{ route('technician.create') }}" class="btn btn-primary"> CREAR TÉCNICO</a>
            </div>
            <!-- Tabla de técnicos -->
            <table id="TechniciansTable" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead class="bg-gradient-2 text-center">
                    <tr>
                        <th>#</th>
                        <th>NOMBRE</th>
                        <th>CORREO</th>
                        <th>ESPECIALIDAD</th>
                        <th>DISPONIBILIDAD</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($technicians as $key => $technician)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $technician->user->name }}</td>
                        <td>{{ $technician->user->email }}</td>
                        <td>{{ $technician->specialty->name }}</td>
                        <td>
                            @if($technician->available)
                            <span class="badge bg-success">Disponible</span>
                            @else
                            <span class="badge bg-danger">No disponible</span>
                            @endif
                        </td>
                        <td>
                            @can('technicians.edit')
                            <a title="Editar técnico" href="{{ route('technician.edit', $technician->id) }}" class="btn btn-green-600 mb-3"><i class='bx bxs-edit-alt'></i></a>
                            @endcan
                            @can('technicians.destroy')
                            <form method="POST" style="display:inline;" action="{{ route('technician.destroy', $technician->id) }}">
                                @method('DELETE')
                                @csrf
                                <button title="Eliminar técnico" class="btn btn-red-800"><i class='bx bxs-trash'></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Técnicos desactivados -->
    <div class="tab-pane fade" id="nav-deletedTechnicians" role="tabpanel" aria-labelledby="nav-deletedTechnicians-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <!-- Tabla de técnicos desactivados -->
            <table id="DeletedTechniciansTable" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead class="bg-gradient-2 text-center">
                    <tr>
                        <th>#</th>
                        <th>NOMBRE</th>
                        <th>ESPECIALIDAD</th>
                        <th>FECHA DE DESACTIVACIÓN</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deletedTechnicians as $key => $technician)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $technician->user->name }}</td>
                        <td>{{ $technician->specialty }}</td>
                        <td>{{ $technician->updated_at }}</td>
                        <td>
                            @can('technician.restore')
                            <form method="POST" action="{{ route('technician.restore', $technician->id) }}">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-cyan-800 mb-3" type="submit">Restaurar</button>
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

<script>
    $(document).ready(function() {
        $('.js-select-departamento').select2({
            placeholder: "Seleccione un departamento",
            theme: "bootstrap-5",
            width: '100%',
        });
    });
</script>

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