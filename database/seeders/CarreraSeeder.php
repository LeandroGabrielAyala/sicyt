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
            'nombre' => 'Farmcia'
        ]);
        Carrera::create([
            'nombre' => 'Contador Público'
        ]);
        Carrera::create([
            'nombre' => 'Ing. en Alimentos'
        ]);
    }
}
