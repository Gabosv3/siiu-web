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
            ],
            'Técnicos' => [
                ['name' => 'technicians.index', 'description' => 'Ver Técnicos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'technicians.create', 'description' => 'Crear Técnicos', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'technicians.edit', 'description' => 'Editar Técnicos', 'roles' => ['SuperAdmin']],
                ['name' => 'technicians.destroy', 'description' => 'Eliminar Técnicos', 'roles' => ['SuperAdmin']],
                ['name' => 'technicians.restore', 'description' => 'Restaurar Técnicos', 'roles' => ['SuperAdmin']],
            ],
            'Hardware' => [
                ['name' => 'hardware.index', 'description' => 'Ver Hardware', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'hardware.create', 'description' => 'Crear Hardware', 'roles' => ['SuperAdmin', 'Administrador']],
                ['name' => 'hardware.edit', 'description' => 'Editar Hardware', 'roles' => ['SuperAdmin']],
                ['name' => 'hardware.destroy', 'description' => 'Eliminar Hardware', 'roles' => ['SuperAdmin']],
                ['name' => 'hardware.restore', 'description' => 'Restaurar Hardware', 'roles' => ['SuperAdmin']],
            ],
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
        $categories = [
            [
                'name' => 'Computadora',
                'description' => 'Computadoras de escritorio',
                'image' => '/storage/categories/37D4HL6kLBPdERxY4stskrhVDgFqQ305aOIUb6iC.jpg' // Imagen que ya existe
            ],
            [
                'name' => 'Laptop',
                'description' => 'Laptops de escritorio',
                'image' => '/storage/categories/37D4HL6kLBPdERxY4stskrhVDgFqQ305aOIUb6iC.jpg' // Imagen que ya existe
            ],
           
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        
    }
}