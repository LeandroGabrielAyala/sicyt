<?php

namespace Database\Seeders;

use App\Models\TipoProyecto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoProyecto::create([
            'nombre' => 'PI - Investigación'
        ]);
        TipoProyecto::create([
            'nombre' => 'PE - Estratégico'
        ]);
    }
}
