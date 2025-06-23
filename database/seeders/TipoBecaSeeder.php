<?php

namespace Database\Seeders;

use App\Models\TipoBeca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoBecaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoBeca::create([
            'nombre' => 'UNCAUS'
        ]);
        TipoBeca::create([
            'nombre' => 'UNCAUS Grado'
        ]);
        TipoBeca::create([
            'nombre' => 'UNCAUS Posgrado'
        ]);
        TipoBeca::create([
            'nombre' => 'CIN'
        ]);
        TipoBeca::create([
            'nombre' => 'CONICET'
        ]);
    }
}
