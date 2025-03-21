<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Definir roles
        $roles = [
            'SuperAdmin',
            'Administrador',
            'Usuario'
        ];

        // Crear o actualizar roles
        $roleInstances = [];
        foreach ($roles as $roleName) {
            $roleInstances[$roleName] = Role::updateOrCreate(['name' => $roleName]);
        }

        // Definir permisos agrupados por entidad
        $groupedPermissions = [
            'Usuarios' => [
                ['name' => 'user.index', 'description' => 'Ver Usuarios', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'user.create', 'description' => 'Crear Usuario', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'user.edit', 'description' => 'Editar Usuario', 'roles' => ['SuperAdmin']],
                ['name' => 'user.destroy', 'description' => 'Eliminar Usuario', 'roles' => ['SuperAdmin']],
                ['name' => 'user.restore', 'description' => 'Restaurar Usuario', 'roles' => ['SuperAdmin']],
            ],
            'Roles' => [
                ['name' => 'role.index', 'description' => 'Ver Roles', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'role.create', 'description' => 'Crear Roles', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'role.edit', 'description' => 'Editar Roles', 'roles' => ['SuperAdmin']],
                ['name' => 'role.destroy', 'description' => 'Eliminar Roles', 'roles' => ['SuperAdmin']],
                ['name' => 'role.restore', 'description' => 'Restaurar Roles', 'roles' => ['SuperAdmin']],
                ['name' => 'role.clone', 'description' => 'Clonar Roles', 'roles' => ['SuperAdmin']],
            ],
            'Departamentos' => [
                ['name' => 'departamentos.index', 'description' => 'Ver Departamentos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'departamentos.create', 'description' => 'Crear Departamentos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'departamentos.edit', 'description' => 'Editar Departamentos', 'roles' => ['SuperAdmin']],
                ['name' => 'departamentos.destroy', 'description' => 'Eliminar Departamentos', 'roles' => ['SuperAdmin']],
                ['name' => 'departamentos.restore', 'description' => 'Restaurar Departamentos', 'roles' => ['SuperAdmin']],
            ],
            'Categorías' => [
                ['name' => 'categorias.index', 'description' => 'Ver Categorías', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'categorias.create', 'description' => 'Crear Categorías', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'categorias.edit', 'description' => 'Editar Categorías', 'roles' => ['SuperAdmin']],
                ['name' => 'categorias.destroy', 'description' => 'Eliminar Categorías', 'roles' => ['SuperAdmin']],
                ['name' => 'categorias.restore', 'description' => 'Restaurar Categorías', 'roles' => ['SuperAdmin']],
            ],
            'Exportación' => [
                ['name' => 'export.copy', 'description' => 'Botón Copiar', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'export.excel', 'description' => 'Botón Excel', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'export.csv', 'description' => 'Botón CSV', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'export.print', 'description' => 'Botón Imprimir', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'export.pdf', 'description' => 'Botón PDF', 'roles' => ['SuperAdmin', 'Administrador']],
            ],
            'General' => [
                ['name' => 'dashboard', 'description' => 'Ver Dashboard', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'Mantenimiento', 'description' => 'Ver Mantenimiento', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'Inventarios', 'description' => 'Ver Inventarios', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'Escaner', 'description' => 'Escanear', 'roles' => ['SuperAdmin', 'Administrador']],
            ],
            'Técnicos' => [
                ['name' => 'technicians.index', 'description' => 'Ver Técnicos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'technicians.create', 'description' => 'Crear Técnicos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'technicians.edit', 'description' => 'Editar Técnicos', 'roles' => ['SuperAdmin']],
                ['name' => 'technicians.destroy', 'description' => 'Eliminar Técnicos', 'roles' => ['SuperAdmin']],
                ['name' => 'technicians.restore', 'description' => 'Restaurar Técnicos', 'roles' => ['SuperAdmin']],
            ],
            'Equipos' => [
                ['name' => 'hardware.index', 'description' => 'Ver Equipo', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'hardware.create', 'description' => 'Crear Equipo', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'hardware.edit', 'description' => 'Editar Equipo', 'roles' => ['SuperAdmin']],
                ['name' => 'hardware.destroy', 'description' => 'Eliminar Equipo', 'roles' => ['SuperAdmin']],
                ['name' => 'hardware.restore', 'description' => 'Restaurar Equipo', 'roles' => ['SuperAdmin']],
            ],'Softwares' => [
                ['name' => 'softwares.index', 'description' => 'Ver Softwares', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'softwares.create', 'description' => 'Crear Softwares', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'softwares.edit', 'description' => 'Editar Softwares', 'roles' => ['SuperAdmin']],
                ['name' => 'softwares.destroy', 'description' => 'Eliminar Softwares', 'roles' => ['SuperAdmin']],
                ['name' => 'softwares.restore', 'description' => 'Restaurar Softwares', 'roles' => ['SuperAdmin']],
            ],'Licencias' => [
                ['name' => 'licencias.index', 'description' => 'Ver Licencias', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'licencias.create', 'description' => 'Crear Licencias', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'licencias.edit', 'description' => 'Editar Licencias', 'roles' => ['SuperAdmin']],
                ['name' => 'licencias.destroy', 'description' => 'izar Licencias', 'roles' => ['SuperAdmin']],
                ['name' => 'licencias.restore', 'description' => 'Restaurar Licencias', 'roles' => ['SuperAdmin']],
            ],'Insumos' => [
                ['name' => 'supply.index', 'description' => 'Ver Insumos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'supply.create', 'description' => 'Crear Insumos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'supply.edit', 'description' => 'Editar Insumos', 'roles' => ['SuperAdmin']],
                ['name' => 'supply.destroy', 'description' => 'Eliminar Insumos', 'roles' => ['SuperAdmin']],
                ['name' => 'supply.restore', 'description' => 'Restaurar Insumos', 'roles' => ['SuperAdmin']],
            ],'Modelos' => [
                ['name' => 'modelos.index', 'description' => 'Ver Modelos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'modelos.create', 'description' => 'Crear Modelos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'modelos.edit', 'description' => 'Editar Modelos', 'roles' => ['SuperAdmin']],
                ['name' => 'modelos.destroy', 'description' => 'Eliminar Modelos', 'roles' => ['SuperAdmin']],
                ['name' => 'modelos.restore', 'description' => 'Restaurar Modelos', 'roles' => ['SuperAdmin']],
            ],'Asignaciones' => [
                ['name' => 'asignar.index', 'description' => 'Ver Asignaciones', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'asignar.create', 'description' => 'Crear Asignaciones', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'asignar.edit', 'description' => 'Editar Asignaciones', 'roles' => ['SuperAdmin']],
                ['name' => 'asignar.destroy', 'description' => 'Eliminar Asignaciones', 'roles' => ['SuperAdmin']],
                ['name' => 'asignar.restore', 'description' => 'Restaurar Asignaciones', 'roles' => ['SuperAdmin']],
            ],'Tickets' => [
                ['name' => 'tickets.index', 'description' => 'Ver Tickets', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'tickets.create', 'description' => 'Crear Tickets', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'tickets.edit', 'description' => 'Editar Tickets', 'roles' => ['SuperAdmin']],
                ['name' => 'tickets.destroy', 'description' => 'Eliminar Tickets', 'roles' => ['SuperAdmin']],
                ['name' => 'tickets.restore', 'description' => 'Restaurar Tickets', 'roles' => ['SuperAdmin']],
            ],'Tickets de Soporte' => [
                ['name' => 'support_tickets.index', 'description' => 'Ver Tickets de Soporte', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'support_tickets.create', 'description' => 'Crear Tickets de Soporte', 'roles' => ['SuperAdmin', 'Administrador']],
                
            ], 'caracteristicas' => [
                ['name' => 'characteristic.index', 'description' => 'Ver Caracteristicas', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'characteristic.create', 'description' => 'Crear Caracteristicas', 'roles' => ['SuperAdmin', 'Administrador']],
            ],'Reportes' => [
                ['name' => 'reportes.index', 'description' => 'Ver Reportes ', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'reportes.insumo', 'description' => 'Ver Reportes de Insumos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'reportes.usuarios', 'description' => 'Ver Reportes de usuarios', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'reportes.tickets', 'description' => 'Ver Reportes de tickets', 'roles' => ['SuperAdmin', 'Administrador']],
            ]
        ];

        // Crear o actualizar permisos y asignar roles
        foreach ($groupedPermissions as $group => $permissions) {
            foreach ($permissions as $permissionData) {
                $permission = Permission::updateOrCreate(
                    ['name' => $permissionData['name']],
                    ['description' => $permissionData['description'], 'group' => $group] // Guardar el grupo
                );

                foreach ($permissionData['roles'] as $roleName) {
                    $roleInstances[$roleName]->givePermissionTo($permission);
                }
            }
        }

        // Crear categorías con imágenes ya existentes en la carpeta pública


        
    }
}