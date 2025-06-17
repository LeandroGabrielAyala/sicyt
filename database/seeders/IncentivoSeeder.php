<?php

namespace Database\Seeders;

use App\Models\Incentivo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncentivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Incentivo::create([
            'categoria' => 'I'
        ]);
        Incentivo::create([
            'categoria' => 'II'
        ]);
        Incentivo::create([
            'categoria' => 'III'
        ]);
        Incentivo::create([
            'categoria' => 'IV'
        ]);
        Incentivo::create([
            'categoria' => 'V'
        ]);
    }
}
