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

    <div class="card rounded shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="lead mb-0">Total asignados:</p>
                    <h4 class="text-primary" id="assigned-permissions">{{ $role->permissions->count() }}</h4>
                </div>
                <div class="col-md-6">
                    <p class="lead mb-0">Total permisos disponibles:</p>
                    <h4 class="txt-green" id="available-permissions">{{ $permissionsGrouped->flatten()->count() }}</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Formulario para actualizar el rol -->
    <form id="role-update-form" data-role-id="{{ $role->id }}">
        @csrf
        @method('PUT')

        <!-- Campo para el nombre del rol -->
        <div class="form-group mb-4">
            <label for="name">Nombre del Rol</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
        </div>


        <!-- Sección para la selección de permisos -->
        <div class="accordion" id="permissionsAccordion">
            @foreach ($permissionsGrouped as $group => $permissions)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{ $group }}">
                    <button class="accordion-button bg-white border border-1 rounded mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group }}" aria-expanded="false" aria-controls="collapse-{{ $group }}">
                        <div class="d-flex align-items-center w-100 justify-content-between">
                            <!-- Nombre del grupo (a la izquierda) -->
                            <label class="form-check-label fw-semibold" style="flex-shrink: 0; margin-right: 10px;">
                                {{ ucfirst($group) }}
                            </label>

                            <!-- Contenedor del interruptor (checkbox) y "Seleccionar todos" -->
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch me-3">
                                    <input class="form-check-input select-group" type="checkbox" id="select-group-{{ $group }}" data-group="{{ $group }}">
                                    <label class="form-check-label fw-semibold ms-2">
                                        Seleccionar todos
                                    </label>
                                </div>

                                <!-- Contador de elementos (a la derecha) -->
                                <span class="ms-3" id="group-{{ $group }}-count">
                                    0 / {{ count($permissions) }}
                                </span>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse-{{ $group }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $group }}" data-bs-parent="#permissionsAccordion">
                    <div class="accordion-body bg-white">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
                            @foreach ($permissions as $permission)
                            <div class="col">
                                <div class="card border-light shadow-sm">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input permission-checkbox" type="checkbox" data-group="{{ $group }}" data-permission-id="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
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
            </div>
            @endforeach
        </div>

    </form>
    @endif
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const roleId = document.querySelector("#role-update-form").dataset.roleId;

        // Función para mostrar alertas
        function showAlert(message, type = 'success') {
            if (!document.querySelector('#alerts-container')) {
                const container = document.createElement('div');
                container.id = 'alerts-container';
                container.style.position = 'fixed';
                container.style.top = '10px';
                container.style.right = '10px';
                container.style.zIndex = 1050;
                document.body.appendChild(container);
            }

            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

            const container = document.querySelector('#alerts-container');
            container.appendChild(alert);

            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 5000);
            }, 5000);
        }

        // Función para actualizar el número de permisos asignados
        function updateAssignedPermissions() {
            const assignedPermissionsCount = document.querySelectorAll('.permission-checkbox:checked').length;
            document.getElementById('assigned-permissions').textContent = assignedPermissionsCount;
        }

        // Función para actualizar el número de permisos disponibles
        function updateAvailablePermissions() {
            const availablePermissionsCount = document.querySelectorAll('.permission-checkbox').length;
            document.getElementById('available-permissions').textContent = availablePermissionsCount;
        }

        // Actualizar el estado del checkbox de grupo según los permisos
        function updateGroupCheckbox(group) {
            const groupCheckbox = document.querySelector(`#select-group-${group}`);
            const groupPermissions = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
            groupCheckbox.checked = Array.from(groupPermissions).every(checkbox => checkbox.checked);
            updateGroupPermissionCount(group); // Update group count dynamically
        }

        // Función para contar los permisos seleccionados por grupo
        function updateGroupPermissionCount(group) {
            const selectedPermissions = document.querySelectorAll(`#permissionsAccordion input[data-group="${group}"]:checked`);
            const totalPermissions = document.querySelectorAll(`#permissionsAccordion input[data-group="${group}"]`).length;
            document.getElementById(`group-${group}-count`).textContent = `${selectedPermissions.length} / ${totalPermissions}`;
        }

        // Actualizar todos los permisos de un grupo
        function updatePermissions(group, isChecked) {
            document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(checkbox => {
                checkbox.checked = isChecked;
                updatePermission(checkbox.dataset.permissionId, group, isChecked);
            });
            const action = isChecked ? 'asignados' : 'removidos';
            showAlert(`Todos los permisos del grupo "${group}" fueron ${action}`, 'success');
            updateAssignedPermissions();
            updateAvailablePermissions();
        }

        // Actualizar permisos o nombre en tiempo real
        function updatePermission(permissionId, group, isChecked) {
            fetch(`/roles/${roleId}/update-permission`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        permission_id: permissionId,
                        group,
                        is_checked: isChecked
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const action = isChecked ? 'asignado' : 'removido';
                        showAlert(`Permiso del grupo "${group}" fue ${action} correctamente`, 'success');
                        updateAssignedPermissions();
                        updateAvailablePermissions();
                    } else {
                        showAlert(`Error al actualizar permiso: ${data.message}`, 'danger');
                    }
                });
        }

        function updateRoleName(newName) {
            fetch(`/roles/${roleId}/update-name`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        name: newName
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(`El nombre del rol fue actualizado a: ${newName}`, 'success');
                    } else {
                        showAlert(`Error al actualizar nombre: ${data.message}`, 'danger');
                    }
                });
        }

        // Manejar cambios en el nombre del rol
        document.querySelector("#name").addEventListener("change", function() {
            updateRoleName(this.value);
        });

        // Manejar cambios en los checkboxes de grupo
        document.querySelectorAll(".select-group").forEach(groupCheckbox => {
            groupCheckbox.addEventListener("change", function() {
                const group = this.id.replace("select-group-", "");
                updatePermissions(group, this.checked);
            });
        });

        // Manejar cambios en los checkboxes de permisos
        document.querySelectorAll(".permission-checkbox").forEach(permissionCheckbox => {
            permissionCheckbox.addEventListener("change", function() {
                const group = this.dataset.group;
                updateGroupCheckbox(group);
                updatePermission(this.dataset.permissionId, group, this.checked);
            });
        });

        // Inicializar estados de los grupos
        document.querySelectorAll(".select-group").forEach(groupCheckbox => {
            const group = groupCheckbox.id.replace("select-group-", "");
            updateGroupCheckbox(group);
        });

        // Inicializar los contadores de permisos
        updateAssignedPermissions();
        updateAvailablePermissions();
    });
</script>


@endsection