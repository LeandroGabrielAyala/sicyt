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
            'nombre' => 'CIN'
        ]);
    }
}
