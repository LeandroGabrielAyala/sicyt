<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cargo::create([
            'nombre' => 'JTP'
        ]);
        Cargo::create([
            'nombre' => 'Exclusivo'
        ]);
        Cargo::create([
            'nombre' => 'Semiexclusivo'
        ]);
    }
}
