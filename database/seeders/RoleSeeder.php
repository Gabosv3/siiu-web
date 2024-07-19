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
        //
        $role1 = Role::create(['name'=> 'SuperAdmin']);
        $role2 = Role::create(['name'=> 'Administrador']);
        Role::create(['name'=> 'Usuario']);

        
      

        Permission::create(['name'=> 'user.index','description' => 'Ver Usuarios'])->syncRoles([$role1,$role2]);
        Permission::create(['name'=> 'user.create','description' => 'Crear Usuario'])->syncRoles([$role1,$role2]);
        Permission::create(['name'=> 'user.edit','description' => 'Editar Usuario'])->syncRoles([$role1]);
        Permission::create(['name'=> 'user.destroy','description' => 'Eliminar Usuario'])->syncRoles([$role1]);
        Permission::create(['name'=> 'user.restore','description' => 'restaurar Usuario'])->syncRoles([$role1]);

        Permission::create(['name'=> 'role.index','description' => 'Ver Roles'])->syncRoles([$role1,$role2]);
        Permission::create(['name'=> 'role.create','description' => 'Crear Roles'])->syncRoles([$role1,$role2]);
        Permission::create(['name'=> 'role.edit','description' => 'Editar Roles'])->syncRoles([$role1]);
        Permission::create(['name'=> 'role.destroy','description' => 'Eliminar Roles'])->syncRoles([$role1]);
        Permission::create(['name'=> 'role.restore','description' => 'restaura Usuario'])->syncRoles([$role1]);

        Permission::create(['name'=> 'departamentos.index','description' => 'Ver departamentos'])->syncRoles([$role1,$role2]);
        Permission::create(['name'=> 'departamentos.create','description' => 'Crear departamentos'])->syncRoles([$role1,$role2]);
        Permission::create(['name'=> 'departamentos.edit','description' => 'Editar departamentos'])->syncRoles([$role1]);
        Permission::create(['name'=> 'departamentos.destroy','description' => 'Eliminar departamentos'])->syncRoles([$role1]);
        Permission::create(['name'=> 'departamentos.restore','description' => 'restaura departamentos'])->syncRoles([$role1]);

        
        Permission::create(['name'=> 'dashboard','description' => 'Ver dashboard'])->syncRoles([$role1,$role2]);
        Permission::create(['name'=> 'tabla','description' => 'botones de tablas'])->syncRoles([$role1,$role2]);
        
    }
}
