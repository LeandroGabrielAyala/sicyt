<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BecarioProyectoSeeder extends Seeder
{
    public function run(): void
    {
        $becarios = DB::table('becarios')->pluck('id')->toArray();
        $proyectos = DB::table('proyectos')->pluck('id')->toArray();
        $convocatorias = DB::table('convocatoria_becas')->pluck('id')->toArray();
        $investigadors = DB::table('investigadors')->pluck('id')->toArray();

        $data = [];

        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'becario_id' => $becarios[array_rand($becarios)],
                'proyecto_id' => $proyectos[array_rand($proyectos)],
                'convocatoria_beca_id' => $convocatorias[array_rand($convocatorias)],
                'director_id' => $investigadors[array_rand($investigadors)],
                'codirector_id' => $investigadors[array_rand($investigadors)],
                'plan_trabajo' => 'Plan de trabajo para becario #' . ($i + 1),
                'tipo_beca' => ['Grado', 'Posgrado', 'CIN'][array_rand(['Grado', 'Posgrado', 'CIN'])],
                'vigente' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('becario_proyecto')->insert($data);
    }
}
