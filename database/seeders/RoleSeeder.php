<?php

namespace Database\Seeders;

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

        // Definir permisos con sus descripciones y roles asociados
        $permissions = [
            ['name' => 'user.index', 'description' => 'Ver Usuarios', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'user.create', 'description' => 'Crear Usuario', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'user.edit', 'description' => 'Editar Usuario', 'roles' => ['SuperAdmin']],
            ['name' => 'user.destroy', 'description' => 'Eliminar Usuario', 'roles' => ['SuperAdmin']],
            ['name' => 'user.restore', 'description' => 'Restaurar Usuario', 'roles' => ['SuperAdmin']],

            ['name' => 'role.index', 'description' => 'Ver Roles', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'role.create', 'description' => 'Crear Roles', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'role.edit', 'description' => 'Editar Roles', 'roles' => ['SuperAdmin']],
            ['name' => 'role.destroy', 'description' => 'Eliminar Roles', 'roles' => ['SuperAdmin']],
            ['name' => 'role.restore', 'description' => 'Restaurar Roles', 'roles' => ['SuperAdmin']],

            ['name' => 'departamentos.index', 'description' => 'Ver Departamentos', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'departamentos.create', 'description' => 'Crear Departamentos', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'departamentos.edit', 'description' => 'Editar Departamentos', 'roles' => ['SuperAdmin']],
            ['name' => 'departamentos.destroy', 'description' => 'Eliminar Departamentos', 'roles' => ['SuperAdmin']],
            ['name' => 'departamentos.restore', 'description' => 'Restaurar Departamentos', 'roles' => ['SuperAdmin']],

            ['name' => 'categorias.index', 'description' => 'Ver Categorías', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'categorias.create', 'description' => 'Crear Categorías', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'categorias.edit', 'description' => 'Editar Categorías', 'roles' => ['SuperAdmin']],
            ['name' => 'categorias.destroy', 'description' => 'Eliminar Categorías', 'roles' => ['SuperAdmin']],
            ['name' => 'categorias.restore', 'description' => 'Restaurar Categorías', 'roles' => ['SuperAdmin']],

            ['name' => 'dashboard', 'description' => 'Ver Dashboard', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'Mantenimiento', 'description' => 'Ver Mantenimiento', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'export.copy', 'description' => 'Boton copiar', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'export.excel', 'description' => 'Boton excel', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'export.csv', 'description' => 'Boton csv', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'export.print', 'description' => 'Boton print', 'roles' => ['SuperAdmin', 'Administrador']],
            ['name' => 'export.pdf', 'description' => 'Boton pdf', 'roles' => ['SuperAdmin', 'Administrador']],
        ];

        // Crear o actualizar permisos y asignar roles
        foreach ($permissions as $permissionData) {
            $permission = Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                ['description' => $permissionData['description']]
            );

            foreach ($permissionData['roles'] as $roleName) {
                $roleInstances[$roleName]->givePermissionTo($permission);
            }
        }
    }
}
