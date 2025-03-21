@extends('layouts.user_type.auth')

@section('content')
<div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
    <div class="row">
        <!-- Sección para el título y la imagen de roles -->
        <div class="col-6 text-center mx-auto">
            <!-- Icono de Roles -->
            <img src="https://cdn-icons-png.flaticon.com/512/5151/5151145.png" width="200px" alt="Icono de Roles">
            <h3 class="center">ROLES</h3>
        </div>

        <!-- Botón para abrir el modal para crear un nuevo rol, solo visible para usuarios con el permiso -->
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            @can('role.create')
            <div class="py-5">
                @can('role.create')
                <button type="button" class="btn btn-rounded btn-md bg-gradient-2" data-bs-toggle="modal"
                    data-bs-target="#createRoleModal" id="btn-modal-crear-role">Crear Role</button>
                @endcan
            </div>
            @endcan
        </div>
    </div>
</div>

<!-- Modal para crear un nuevo rol -->
<div class="modal fade" id="createRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createRoleModalLabel">Crear Role</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="btn-cerrar-modal-role"></button>
            </div>
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <!-- Formulario para crear un nuevo rol -->
                <form class="row g-3 text-dark text-center" action="" method="POST" class="m-auto w-form">
                    @csrf
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Rol</label>
                        <input name="name" type="text"
                            class="border-dark form-control @error('name') is-invalid @enderror" id="name"
                            aria-describedby="nameHelp" required minlength="4" pattern="[A-Za-z\s]+"
                            title="Ingrese solo letras y espacios" value="{{ old('name', $role->name ?? '') }}">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <!-- Botón para registrar el nuevo rol -->
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn bg-gradient-2" id="btn-crear-role">REGISTRAR</button>
                    </div>
                </form>
            </div>
            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    id="btn-cerrar-modal-role">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('role.index') }}" class="mb-4">
    <div class="row align-items-center">
        <!-- Filtro de Estado (Activo/Inactivo) -->
        <div class="col-md-3 mb-3">
            <label for="status" class="form-label d-flex align-items-center">
                <i class="fas fa-toggle-on me-2"></i> Estado
            </label>
            <select name="status" class="form-select" id="status" onchange="this.form.submit()">
                <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Activos</option>
                <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>Desactivados</option>
            </select>
        </div>

        <!-- Buscador de Roles -->
        <div class="col-md-6 mb-3">
            <label for="search" class="form-label d-flex align-items-center">
                <i class="fas fa-search me-2"></i> Buscar rol
            </label>
            <input type="text" name="search" class="form-control" id="search" placeholder="Buscar..."
                value="{{ request()->get('search') }}">
        </div>

        <!-- Selector de cantidad de registros por página -->
        <div class="col-md-3 mb-3">
            <label for="perPage" class="form-label d-flex align-items-center">
                <i class="fas fa-list me-2"></i> Registros por página
            </label>
            <select name="perPage" class="form-select" id="perPage" onchange="this.form.submit()">
                <option value="10" {{ request()->get('perPage') == '10' ? 'selected' : '' }}>10 registros</option>
                <option value="20" {{ request()->get('perPage') == '20' ? 'selected' : '' }}>20 registros</option>
                <option value="50" {{ request()->get('perPage') == '50' ? 'selected' : '' }}>50 registros</option>
                <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>Todos</option>
            </select>
        </div>
    </div>
</form>

<div class="table-responsive shadow-lg p-3 mb-5 bg-body rounded rounded-3">
    <table id="Principal" class="table align-items-center mb-0 text-center" style="width:100%">
        <thead class="align-middle bg-gradient-2">
            <tr>
                @if (request()->get('status') == 'inactive')
                <th>#</th>
                <th>Rol</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
                @else
                <th>#</th>
                <th>Rol</th>
                <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <!-- Iterar sobre los roles (activos y eliminados) -->
            @foreach ($roles as $role)
            <tr>
                @if (request()->get('status') == 'inactive')
                <!-- Roles eliminados -->
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->deleted_at }}</td>
                <td>
                    @can('role.restore')
                    <form action="{{ route('role.restore', $role->id) }}" method="POST" class="formulario-restaurar">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success">Restaurar</button>
                    </form>
                    @endcan
                </td>
                @else
                <!-- Roles activos -->
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    <!-- Botones de acción: editar, eliminar, clonar -->
                    @can('role.edit')
                    <a title="Editar rol" href="{{ route('role.edit', $role->id) }}" class="btn btn-green-600 mb-3"><i class='bx bxs-edit-alt'></i></a>
                    @endcan
                    @can('role.destroy')
                    <form method="POST" class="formulario-eliminar" style="display:inline;" action="{{ route('role.destroy', $role->id) }}">
                        @method('DELETE')
                        @csrf
                        <button title="Eliminar rol" class="btn btn-red-800"><i class='bx bxs-trash'></i></button>
                    </form>
                    @endcan
                    @can('role.clone')
                    <button title="Clonar rol" class="btn btn-info mb-3 btn-clonar-rol" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}" data-bs-toggle="modal" data-bs-target="#modalClonarRol">
                        <i class='bx bxs-copy'></i>
                    </button>
                    @endcan
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Paginación solo si es una instancia de paginación -->
<div class="pagination-container">
    @if ($roles instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $roles->appends(request()->query())->links('pagination::bootstrap-4') }}
    @endif
</div>

<!-- Modal para Clonar Rol -->
<div class="modal fade" id="modalClonarRol" tabindex="-1" aria-labelledby="modalClonarRolLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalClonarRolLabel">Clonar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-clonar-rol">
                    @csrf
                    <!-- Campo oculto para el ID del rol -->
                    <input type="hidden" id="role-id" name="role-id">
                    <!-- Campo para el nombre del nuevo rol -->
                    <div class="mb-3">
                        <label for="role-name" class="form-label">Nuevo Nombre del Rol</label>
                        <input type="text" class="form-control" id="role-name" name="name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btn-clonar-modal" class="btn btn-primary">Clonar</button>
            </div>
        </div>
    </div>
</div>

<!-- Definir permisos en una variable global -->
@include('components.script-btn')

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
            text: 'Rol {{ strtolower($sessionKey) }} correctamente.' // Texto con la acción en minúscula
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
            text: 'Rol no se pudo {{ strtolower($sessionKey) }}' // Texto con la acción en minúscula
        });
    });
</script>
@endif
@endforeach

<script>
    $(document).on('click', '.btn-clonar-rol', function() {
        var roleId = $(this).data('role-id'); // Obtener el ID del rol
        var roleName = $(this).data('role-name'); // Obtener el nombre del rol

        // Completar el campo del modal con el nombre del rol
        $('#role-name').val(roleName);
        $('#role-id').val(roleId);
    });

    // Código para enviar el formulario del modal al backend
    $('#btn-clonar-modal').on('click', function() {
        var roleId = $('#role-id').val(); // Obtener el ID desde el input oculto
        var roleName = $('#role-name').val(); // Obtener el nombre desde el input del modal

        $.ajax({
            url: '/role/' + roleId + '/clone', // Ruta para clonar el rol
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: roleName
            },
            success: function(response) {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Rol clonado con éxito',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#modalClonarRol').modal('hide');
                    location.reload(); // Recargar la página o actualizar la lista
                });
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al clonar el rol',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });
            }
        });
    });
</script>
@endsection