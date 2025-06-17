<?php

namespace Database\Seeders;

use App\Models\CategoriaInterna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoriaInterna::create([
            'categoria' => '1U'
        ]);
        CategoriaInterna::create([
            'categoria' => '2U'
        ]);
        CategoriaInterna::create([
            'categoria' => '3U'
        ]);
        CategoriaInterna::create([
            'categoria' => '4U'
        ]);
        CategoriaInterna::create([
            'categoria' => '5U'
        ]);
    }
}
