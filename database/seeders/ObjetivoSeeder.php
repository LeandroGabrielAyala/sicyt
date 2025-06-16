<?php

namespace Database\Seeders;

use App\Models\Objetivo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObjetivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Objetivo::create([
            'nombre' => 'Objetivos 1'
        ]);

        Objetivo::create([
            'nombre' => 'Objetivos 2'
        ]);

        Objetivo::create([
            'nombre' => 'Objetivos 3'
        ]);
    }
}
