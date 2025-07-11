<?php

namespace Database\Seeders;

use App\Models\ConvocatoriaBeca;
use App\Models\TipoBeca;
use Illuminate\Database\Seeder;

class ConvocatoriaBecasSeeder extends Seeder
{
    public function run(): void
    {
        $tipoBeca = TipoBeca::first();

        ConvocatoriaBeca::insert([
            [
                'tipo_beca_id' => $tipoBeca->id,
                'anio' => 2023,
                'inicio' => '2023-01-15',
                'fin' => '2023-06-30',
                'estado' => true,
                'disposicion' => 'Disposición 123/23',
                'resolucion' => 'Resolución 456/23',
                'pdf_disposicion' => json_encode(['disposicion1.pdf']),
                'pdf_resolucion' => json_encode(['resolucion1.pdf']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_beca_id' => $tipoBeca->id,
                'anio' => 2024,
                'inicio' => '2024-02-01',
                'fin' => '2024-07-31',
                'estado' => true,
                'disposicion' => 'Disposición 789/24',
                'resolucion' => 'Resolución 101/24',
                'pdf_disposicion' => json_encode(['disposicion2.pdf']),
                'pdf_resolucion' => json_encode(['resolucion2.pdf']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_beca_id' => $tipoBeca->id,
                'anio' => 2022,
                'inicio' => '2022-03-01',
                'fin' => '2022-08-31',
                'estado' => false,
                'disposicion' => 'Disposición 202/22',
                'resolucion' => 'Resolución 303/22',
                'pdf_disposicion' => json_encode(['disposicion3.pdf']),
                'pdf_resolucion' => json_encode(['resolucion3.pdf']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_beca_id' => $tipoBeca->id,
                'anio' => 2021,
                'inicio' => '2021-04-01',
                'fin' => '2021-09-30',
                'estado' => false,
                'disposicion' => 'Disposición 404/21',
                'resolucion' => 'Resolución 505/21',
                'pdf_disposicion' => json_encode(['disposicion4.pdf']),
                'pdf_resolucion' => json_encode(['resolucion4.pdf']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_beca_id' => $tipoBeca->id,
                'anio' => 2020,
                'inicio' => '2020-05-01',
                'fin' => '2020-10-31',
                'estado' => false,
                'disposicion' => 'Disposición 606/20',
                'resolucion' => 'Resolución 707/20',
                'pdf_disposicion' => json_encode(['disposicion5.pdf']),
                'pdf_resolucion' => json_encode(['resolucion5.pdf']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
