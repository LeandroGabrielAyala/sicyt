<?php

namespace Database\Seeders;

use App\Models\NivelAcademico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NivelAcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NivelAcademico::create([
            'nombre' => 'Grado'
        ]);
        NivelAcademico::create([
            'nombre' => 'Especialización'
        ]);
        NivelAcademico::create([
            'nombre' => 'Maestría'
        ]);
        NivelAcademico::create([
            'nombre' => 'Doctorado y posdoctorado'
        ]);
        NivelAcademico::create([
            'nombre' => 'Otros'
        ]);
    }
}