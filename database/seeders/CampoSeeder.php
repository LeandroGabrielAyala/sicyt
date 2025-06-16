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
            'nombre' => 'Campo Socio 1'
        ]);

        Campo::create([
            'nombre' => 'Campo Socio 2'
        ]);

        Campo::create([
            'nombre' => 'Campo Socio 3'
        ]);
    }
}
