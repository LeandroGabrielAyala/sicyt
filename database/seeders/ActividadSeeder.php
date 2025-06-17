<?php

namespace Database\Seeders;

use App\Models\Actividad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Actividad::create([
            'nombre' => 'Investigación Básica'
        ]);

        Actividad::create([
            'nombre' => 'Investigación Aplicada'
        ]);

        Actividad::create([
            'nombre' => 'Desarrollo Experimental'
        ]);
    }
}
