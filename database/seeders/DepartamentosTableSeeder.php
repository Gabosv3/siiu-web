<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Carbon\Carbon;
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
        if (DB::table('departamentos')->count() > 0) {
            return; // Si ya hay registros, no hace nada
        }


        Departamento::insert([
            [
                'codigo' => '3201',
                'nombre' => 'DECANATO',
                'encargado' => '1',
                'descripcion' => 'UNIDAD DE DECANATO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '32011',
                'nombre' => 'PLANIFICACION',
                'encargado' => '2',
                'descripcion' => 'UNIDAD DE PLANIFICACION',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'codigo' => '3202',
                'nombre' => 'ADMINISTRACIÓN FINANCIERA',
                'encargado' => '3',
                'descripcion' => 'UNIDAD DE ADMINISTRACIÓN FINANCIERA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '32022',
                'nombre' => 'ADMINISTRACIÓN GENERAL',
                'encargado' => '4',
                'descripcion' => 'UNIDAD DE ADMINISTRACIÓN GENERAL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3203',
                'nombre' => 'ADMINISTRACIÓN ACADÉMICA',
                'encargado' => '5',
                'descripcion' => 'UNIDAD ADMINISTRACIÓN ACADÉMICA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3204',
                'nombre' => 'BIBLIOTECA',
                'encargado' => '6',
                'descripcion' => 'UNIDAD DE BIBLIOTECA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3205',
                'nombre' => 'DEPARTAMENTO DE CIENCIAS NATURALES Y MATEMÁTICA',
                'encargado' => '7',
                'descripcion' => 'DEPARTAMENTO DE MATEMÁTICA Y CIENCIAS NATURALES',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3206',
                'nombre' => 'FISICA',
                'encargado' => '8',
                'descripcion' => 'UNIDAD DE FISICA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3207',
                'nombre' => 'BIOLOGIA',
                'encargado' => '9',
                'descripcion' => 'UNIDAD DE BIOLOGIA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3208',
                'nombre' => 'QUIMICA Y FARMACIA',
                'encargado' => '10',
                'descripcion' => 'UNIDAD DE QUÍMICA Y FARMACIA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3209',
                'nombre' => 'CIENCIAS AGROQUÍMICAS',
                'encargado' => '11',
                'descripcion' => 'UNIDAD DE CIENCIAS AGROQUÍMICAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3210',
                'nombre' => 'CIENCIAS ECONÓMICAS',
                'encargado' => '12',
                'descripcion' => 'UNIDAD DE CIENCIAS ECONÓMICAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3211',
                'nombre' => 'CIENCIAS JURÍDICAS',
                'encargado' => '13',
                'descripcion' => 'UNIDAD DE CIENCIAS JURÍDICAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3212',
                'nombre' => 'PSICOLOGÍA',
                'encargado' => '14',
                'descripcion' => 'UNIDAD DE PSICOLOGÍA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3213',
                'nombre' => 'EDUCACIÓN',
                'encargado' => '15',
                'descripcion' => 'UNIDAD DE EDUCACIÓN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3214',
                'nombre' => 'CIENCIAS Y HUMANIDADES',
                'encargado' => '16',
                'descripcion' => 'UNIDAD DE CIENCIAS Y HUMANIDADES',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3215',
                'nombre' => 'LETRAS',
                'encargado' => '17',
                'descripcion' => 'SECCIÓN DE LETRAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3216',
                'nombre' => 'SECCIÓN DE IDIOMAS',
                'encargado' => '18',
                'descripcion' => 'UNIDAD DE SECCIÓN DE IDIOMAS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3220',
                'nombre' => 'DEPARTAMENTO DE INGENIERÍA Y ARQUITECTURA',
                'encargado' => '19',
                'descripcion' => 'UNIDAD DE INGENIERÍA Y ARQUITECTURA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '3221',
                'nombre' => 'MEDICINA',
                'encargado' => '20',
                'descripcion' => 'UNIDAD DE MEDICINA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '6001',
                'nombre' => 'ESCUELA DE CARRERAS TÉCNICAS, SEDE MORAZAN',
                'encargado' => '21',
                'descripcion' => 'UNIDAD DE ESCUELA DE CARRERAS TÉCNICAS, SEDE MORAZAN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '0',
                'nombre' => 'SIN UNIDAD',
                'encargado' => '22',
                'descripcion' => 'NO SE CUENTA CON ENCARGADO DE UNIDAD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => '1',
                'nombre' => 'UNIDAD DE ADMINISTRACIÓN DEL SISTEMA',
                'encargado' => '23',
                'descripcion' => 'UNIDAD ESPECIAL, PARA BLOQUEAR A LOS ADMINISTRADORES VISUALIZAR LOS ACTIVOS Y ACCIONES ESPECIALES PARA LOS MODULOS DE DOCENTES, ETC.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
