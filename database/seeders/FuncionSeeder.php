<?php

namespace Database\Seeders;

use App\Models\Funcion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuncionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Funcion::create([
            'nombre' => 'Investigador'
        ]);
        Funcion::create([
            'nombre' => 'Personal de Apoyo'
        ]);
        Funcion::create([
            'nombre' => 'Personal Técnico'
        ]);
    }
}
