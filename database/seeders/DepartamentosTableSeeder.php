<?php

namespace Database\Seeders;

use App\Models\Departament;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verifica si ya existen registros en la tabla
        if (DB::table('departaments')->count() > 0) {
            return; // Si ya hay registros, no hace nada
        }


        Departament::insert([
            [
                'code' => '3201',
                'name' => 'DECANATO',
                'manager' => '1',
                'description' => 'UNIDAD DE DECANATO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '32011',
                'name' => 'PLANIFICACION',
                'manager' => '2',
                'description' => 'UNIDAD DE PLANIFICACION',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'code' => '3202',
                'name' => 'ADMINISTRACIÓN FINANCIERA',
                'manager' => '3',
                'description' => 'UNIDAD DE ADMINISTRACIÓN FINANCIERA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '32022',
                'name' => 'ADMINISTRACIÓN GENERAL',
                'manager' => '4',
                'description' => 'UNIDAD DE ADMINISTRACIÓN GENERAL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3203',
                'name' => 'ADMINISTRACIÓN ACADÉMICA',
                'manager' => '5',
                'description' => 'UNIDAD ADMINISTRACIÓN ACADÉMICA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3204',
                'name' => 'BIBLIOTECA',
                'manager' => '6',
                'description' => 'UNIDAD DE BIBLIOTECA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3205',
                'name' => 'DEPARTAMENTO DE CIENCIAS NATURALES Y MATEMÁTICA',
                'manager' => '7',
                'description' => 'DEPARTAMENTO DE MATEMÁTICA Y CIENCIAS NATURALES',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3206',
                'name' => 'FISICA',
                'manager' => '8',
                'description' => 'UNIDAD DE FISICA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3207',
                'name' => 'BIOLOGIA',
                'manager' => '9',
                'description' => 'UNIDAD DE BIOLOGIA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3208',
                'name' => 'QUIMICA Y FARMACIA',
                'manager' => '10',
                'description' => 'UNIDAD DE QUÍMICA Y FARMACIA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3209',
                'name' => 'CIENCIAS AGROQUÍMICAS',
                'manager' => '11',
                'description' => 'UNIDAD DE CIENCIAS AGROQUÍMICAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3210',
                'name' => 'CIENCIAS ECONÓMICAS',
                'manager' => '12',
                'description' => 'UNIDAD DE CIENCIAS ECONÓMICAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3211',
                'name' => 'CIENCIAS JURÍDICAS',
                'manager' => '13',
                'description' => 'UNIDAD DE CIENCIAS JURÍDICAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3212',
                'name' => 'PSICOLOGÍA',
                'manager' => '14',
                'description' => 'UNIDAD DE PSICOLOGÍA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3213',
                'name' => 'EDUCACIÓN',
                'manager' => '15',
                'description' => 'UNIDAD DE EDUCACIÓN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3214',
                'name' => 'CIENCIAS Y HUMANIDADES',
                'manager' => '16',
                'description' => 'UNIDAD DE CIENCIAS Y HUMANIDADES',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3215',
                'name' => 'LETRAS',
                'manager' => '17',
                'description' => 'SECCIÓN DE LETRAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3216',
                'name' => 'SECCIÓN DE IDIOMAS',
                'manager' => '18',
                'description' => 'UNIDAD DE SECCIÓN DE IDIOMAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3220',
                'name' => 'DEPARTAMENTO DE INGENIERÍA Y ARQUITECTURA',
                'manager' => '19',
                'description' => 'UNIDAD DE INGENIERÍA Y ARQUITECTURA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3221',
                'name' => 'MEDICINA',
                'manager' => '20',
                'description' => 'UNIDAD DE MEDICINA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '6001',
                'name' => 'ESCUELA DE CARRERAS TÉCNICAS, SEDE MORAZAN',
                'manager' => '21',
                'description' => 'UNIDAD DE ESCUELA DE CARRERAS TÉCNICAS, SEDE MORAZAN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '0',
                'name' => 'SIN UNIDAD',
                'manager' => '22',
                'description' => 'NO SE CUENTA CON ENCARGADO DE UNIDAD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1',
                'name' => 'UNIDAD DE ADMINISTRACIÓN DEL SISTEMA',
                'manager' => '23',
                'description' => 'UNIDAD ESPECIAL, PARA BLOQUEAR A LOS ADMINISTRADORES VISUALIZAR LOS ACTIVOS Y ACCIONES ESPECIALES PARA LOS MODULOS DE DOCENTES, ETC.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
