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
            'nombre' => 'Exploración y explotación de la tierra'
        ]);	
        Objetivo::create([
            'nombre' => 'Medio ambiente'
        ]);	
        Objetivo::create([
            'nombre' => 'Exploración y explotación del espacio'
        ]);	
        Objetivo::create([
            'nombre' => 'Transporte, telecomunicación y otras infraestructuras'
        ]);	
        Objetivo::create([
            'nombre' => 'Energía'
        ]);	
        Objetivo::create([
            'nombre' => 'Producción y tecnología industrial'
        ]);	
        Objetivo::create([
            'nombre' => 'Salud'
        ]);	
        Objetivo::create([
            'nombre' => 'Agricultura'
        ]);	
        Objetivo::create([
            'nombre' => 'Educación'
        ]);	
        Objetivo::create([
            'nombre' => 'Cultura, recreación, religión y medios de comunicación'
        ]);	
        Objetivo::create([
            'nombre' => 'Estructura, procesos y sistemas políticos y sociales'
        ]);	
        Objetivo::create([
            'nombre' => 'Producción general de conocimiento'
        ]);	
        Objetivo::create([
            'nombre' => 'Defensa'
        ]);
    }
}


