<?php

namespace Database\Seeders;

use App\Models\Campo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campo::create([
            'nombre' => 'Ciencias exactas y naturales'
        ]);

        Campo::create([
            'nombre' => 'Ingeniería y tecnología'
        ]);

        Campo::create([
            'nombre' => 'Ciencias médicas'
        ]);
        Campo::create([
            'nombre' => 'Ciencias agrícolas y veterinarias'
        ]);

        Campo::create([
            'nombre' => 'Ciencias sociales'
        ]);

        Campo::create([
            'nombre' => 'Humanidades y artes'
        ]);
    }
}