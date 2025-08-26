<?php

namespace Database\Seeders;

use App\Models\Rubro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RubroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rubro::create([
            'nombre' => 'Bienes de Consumo',
        ]);
        Rubro::create([
            'nombre' => 'Bienes de Uso',
        ]);
        Rubro::create([
            'nombre' => 'Bienes de Capital',
        ]);
        Rubro::create([
            'nombre' => 'Reintegro',
        ]);
        Rubro::create([
            'nombre' => 'Servicio',
        ]);
        Rubro::create([
            'nombre' => 'Honorarios',
        ]);
        Rubro::create([
            'nombre' => 'Consultoria',
        ]);
    }
}
