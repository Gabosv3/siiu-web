@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h2>Editar Rol</h2>

    @if($role->name === 'SuperAdmin')
    <!-- Mensaje informando que el rol "SuperAdmin" no puede ser editado -->
    <div class="alert bg-gradient-2 text-white" role="alert">
        El rol "SuperAdmin" no puede ser editado.
    </div>
    @else
    <!-- Formulario para actualizar el rol -->
    <form action="{{ route('role.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campo para el nombre del rol -->
        <div class="form-group">
            <label for="name">Nombre del Rol</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
        </div>

        <!-- Sección para la selección de permisos -->
        <div class="form-group">
            <label>Permisos</label>

            <!-- Iteramos sobre los grupos de permisos -->
            @foreach ($permissionsGrouped as $group => $permissions)
            <div class="card mb-3">
                <div class="card-header">
                    <!-- Checkbox para seleccionar/desmarcar todos los permisos del grupo -->
                    <div class="form-check form-switch">
                        <input class="form-check-input select-group" type="checkbox" id="select-group-{{ $group }}">
                        <label class="form-check-label" for="select-group-{{ $group }}">
                            Seleccionar todos en {{ ucfirst($group) }}
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-5">
                        <!-- Iteramos sobre los permisos del grupo -->
                        @foreach ($permissions as $permission)
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <!-- Checkbox para cada permiso con el atributo data-group -->
                                        <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}"
                                            data-group="{{ $group }}"
                                            {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                            {{ $permission->description }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-md-12 text-end">
            <!-- Botón para actualizar -->
            <button type="submit" class="btn btn-primary" id="btn-actualizar-role">Actualizar</button>
        </div>
    </form>
    @endif
</div>
<script>
    document.querySelectorAll('.select-group').forEach(function(groupCheckbox) {
        groupCheckbox.addEventListener('change', function() {
            // Obtener el nombre del grupo a partir del ID del checkbox de grupo
            const group = this.id.replace('select-group-', '');

            // Seleccionar solo los checkboxes de permisos que pertenecen al grupo actual
            const checkboxes = document.querySelectorAll(`.permission-checkbox[id^="permission-"][data-group="${group}"]`);

            // Marcar o desmarcar todos los checkboxes del grupo
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = groupCheckbox.checked;
            });
        });
    });
</script>

@endsection