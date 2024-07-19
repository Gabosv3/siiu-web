<?php

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\InformacionPersonal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('informacion_personal')->truncate();
        DB::statement('ALTER TABLE informacion_personal AUTO_INCREMENT = 1;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call(RoleSeeder::class);

        Departamento::create([
            'nombre' => 'Sistemas Informaticos',
        ]);
        
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'), // Recuerda cambiar esto por la contraseña real
            'departamento_id' => 1,
        ])->assignRole('SuperAdmin');

        User::create([
            'name' => 'Gabriel',
            'email' => 'Gabriel@example.com',
            'password' => Hash::make('1234'), // Recuerda cambiar esto por la contraseña real
            'departamento_id' => 1,
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Azucena',
            'email' => 'Azucena@example.com',
            'password' => Hash::make('1234'), // Recuerda cambiar esto por la contraseña real
            'departamento_id' => 1,
        ])->assignRole('Usuario');

        

        
        
    }
}
