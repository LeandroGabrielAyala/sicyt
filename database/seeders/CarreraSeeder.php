<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carrera::create([
            'nombre' => 'Farmcia',
            'titulo' => 'Farmaceutico'
        ]);
        Carrera::create([
            'nombre' => 'Contador Público',
            'titulo' => 'Contador Público'
        ]);
        Carrera::create([
            'nombre' => 'Ing. en Alimentos',
            'titulo' => 'Ingeniero en Alimentos'
        ]);
        Carrera::create([
            'nombre' => 'Abogacia',
            'titulo' => 'Abogado'
        ]);
        Carrera::create([
            'nombre' => 'Ing. Industrial',
            'titulo' => 'Ingeniero Industrial'
        ]);
        Carrera::create([
            'nombre' => 'Ing. en Sistemas',
            'titulo' => 'Ingeniero en Sistemas'
        ]);
    }
}
