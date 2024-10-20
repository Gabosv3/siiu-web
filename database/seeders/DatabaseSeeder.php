<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Specialty;
use App\Models\Technician;
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
    
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('personal_informations')->truncate();
        DB::statement('ALTER TABLE personal_informations AUTO_INCREMENT = 1;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call(RoleSeeder::class);
        $this->call(DepartamentosTableSeeder::class);
                
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'), // Recuerda cambiar esto por la contraseña real
            'departament_id' => 1,
            'email_verified_at' => now(), // Establece la fecha de verificación de email
        ])->assignRole('SuperAdmin');
        
        User::create([
            'name' => 'Gabriel',
            'email' => 'Gabriel@example.com',
            'password' => Hash::make('1234'), // Recuerda cambiar esto por la contraseña real
            'departament_id' => 1,
            'email_verified_at' => now(), // Establece la fecha de verificación de email
        ])->assignRole('Administrador');
        
        User::create([
            'name' => 'Azucena',
            'email' => 'Azucena@example.com',
            'password' => Hash::make('1234'), // Recuerda cambiar esto por la contraseña real
            'departament_id' => 1,
            'email_verified_at' => now(), // Establece la fecha de verificación de email
        ])->assignRole('Usuario');

        Specialty::create([
            'name' => 'Soporte Técnico',
        ]);

        Technician::create([
            'user_id' => 1, // ID del usuario asociado
            'specialty_id' => 1, // Especialidad del técnico
            'available' => true, // Indica si el técnico está disponible
        ]);

      

    }
}
