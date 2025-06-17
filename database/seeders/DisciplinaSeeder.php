<?php

namespace Database\Seeders;

use App\Models\Disciplina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisciplinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Disciplina::create([
            'nombre' => 'Biología',
            'campo_id' => 1
        ]);
        Disciplina::create([
            'nombre' => 'Física',
            'campo_id' => 1
        ]);
        Disciplina::create([
            'nombre' => 'Genética',
            'campo_id' => 1
        ]);
        Disciplina::create([
            'nombre' => 'Geografía',
            'campo_id' => 2
        ]);
        Disciplina::create([
            'nombre' => 'Geología',
            'campo_id' => 2
        ]);
        Disciplina::create([
            'nombre' => 'Matemática',
            'campo_id' => 3
        ]);
        Disciplina::create([
            'nombre' => 'Química',
            'campo_id' => 3
        ]);
        Disciplina::create([
            'nombre' => 'Otras',
            'campo_id' => 4
        ]);
    }
}