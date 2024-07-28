@extends('layouts.user_type.auth')

@section('content')
<div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
    <div class="row">
        <!-- Sección para el título y la imagen de roles -->
        <div class="col-4 text-center mx-auto">
            <!-- Icono de Roles -->
            <img src="https://cdn-icons-png.flaticon.com/512/5151/5151145.png" width="200px" alt="Icono de Roles">
            <h3 class="center">ROLES</h3>
        </div>
        <div class="col-4 text-center mx-auto"></div>
        <!-- Botón para abrir el modal para crear un nuevo rol, solo visible para usuarios con el permiso -->
        <div class="col-md-4 d-flex justify-content-center align-items-center">
            @can('role.create')
            <div class="py-5">
                <button type="button" class="btn btn-rounded btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">Crear Role</button>
            </div>
            @endcan
        </div>
    </div>
</div>

<!-- Modal para crear un nuevo rol -->
<div class="modal fade" id="createRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createRoleModalLabel">Crear Role</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-activar-modal-role"></button>
            </div>
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <!-- Formulario para crear un nuevo rol -->
                <form class="row g-3 text-dark text-center" action="" method="POST" class="m-auto w-form">
                    @csrf
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Rol</label>
                        <input name="name" type="text" class="border-dark form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp" required minlength="4" pattern="[A-Za-z\s]+" title="Ingrese solo letras y espacios" value="{{ old('name', $role->name ?? '') }}">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <!-- Botón para registrar el nuevo rol -->
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn btn-primary" id="btn-activar-modal-role">REGISTRAR</button>
                    </div>
                </form>
            </div>
            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-crear-role">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <!-- Pestaña para mostrar los roles -->
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Roles</button>
        <!-- Pestaña para mostrar los roles eliminados -->
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Eliminados</button>
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <!-- Contenido de la pestaña de roles -->
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="Principal" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead class="table-primary text-center">
                    <tr>
                        <th class="w-25">#</th>
                        <th class="w-50">ROLES</th>
                        <th class="w-15">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterar sobre la lista de roles -->
                    @foreach ($roles as $key => $role)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $role->name }}</td>
                        <td>
                            <div class="row gx-3">
                                <!-- Botón para editar el rol (visible solo para usuarios con permiso) -->
                                @can('role.edit')
                                <div class="col">
                                    <a href="{{ route('role.edit', $role->id) }}" class="btn btn-green-600 mb-3" id="btn-redireccionar-editar-rol"><i class='bx bxs-edit-alt'></i></a>
                                </div>
                                @endcan
                                <!-- Botón para eliminar el rol (visible solo para usuarios con permiso) -->
                                @can('role.destroy')
                                <div class="col">
                                    <form method="POST" class="formulario-eliminar" action="{{ route('role.destroy', $role->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-red-800"><i class='bx bxs-trash' id="btn-eliminar-rol"></i></button>
                                    </form>
                                </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Contenido de la pestaña de roles eliminados -->
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Rol</th>
                        <th>Fecha Eliminación</th>
                        <th class="w-15">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterar sobre la lista de roles eliminados -->
                    @foreach ($rolesDelets as $key => $role)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->deleted_at }}</td>
                        <td>
                            <!-- Formulario para restaurar el rol --> 
                             @can('role.restore')
                            <form action="{{ route('role.restore', $role->id) }}" class="formulario-restaurar" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-cyan-800 mb-3" type="submit" id="btn-restaurar-rol">Restaurar</button>
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

<!-- Definir permisos en una variable global -->
<script>
    window.permissions = {
        copy: {{ json_encode(auth()->user()->can('export.copy')) }},
        excel: {{ json_encode(auth()->user()->can('export.excel')) }},
        csv: {{ json_encode(auth()->user()->can('export.csv')) }},
        pdf: {{ json_encode(auth()->user()->can('export.pdf')) }},
        print: {{ json_encode(auth()->user()->can('export.print')) }}
    };
</script>

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>
<script src="{{ asset('assets/js/Roles/RolesIndex.js') }}"></script>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Muestra el SweetAlert con el mensaje de error
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Role no se pudo crear'
        }).then(() => {
            // Después de cerrar el SweetAlert, abre el modal automáticamente
            var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            modal.show();
        });
    });
</script>
@endif

<!-- Iterar sobre los tipos de sesión: agregado, eliminado, Actualizado, Restaurado -->
@foreach (['agregado', 'eliminado', 'Actualizado', 'Restaurado'] as $sessionKey)
    <!-- Verificar si la sesión tiene el valor 'SI' -->
    @if (session($sessionKey) == 'SI')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success', // Ícono de éxito
                    title: '{{ ucfirst($sessionKey) }}', // Título con la primera letra en mayúscula
                    text: 'Usuario {{ strtolower($sessionKey) }} correctamente.' // Texto con la acción en minúscula
                });
            });
        </script>
    <!-- Verificar si la sesión tiene el valor 'NO' -->
    @elseif (session($sessionKey) == 'NO')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error', // Ícono de error
                    title: 'Error', // Título de error
                    text: 'Usuario no se pudo {{ strtolower($sessionKey) }}' // Texto con la acción en minúscula
                });
            });
        </script>
    @endif
@endforeach


@endsection