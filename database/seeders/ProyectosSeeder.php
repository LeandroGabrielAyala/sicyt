<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\Proyecto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectosSeeder extends Seeder
{
    public function run(): void
    {
        $carrera = Carrera::first();
        $campos = DB::table('campos')->pluck('id')->toArray();
        $objetivos = DB::table('objetivos')->pluck('id')->toArray();
        $actividades = DB::table('actividads')->pluck('id')->toArray(); // Verificar nombre exacto de tabla

        if (empty($campos) || empty($objetivos) || empty($actividades)) {
            $this->command->warn('No hay datos suficientes en campos, objetivos o actividades.');
            return;
        }

        Proyecto::insert([
            [
                'nro' => 101,
                'nombre' => 'Proyecto de Energías Renovables',
                'resumen' => 'Investigación sobre nuevas tecnologías en energía solar y eólica.',
                'carrera_id' => $carrera->id,
                'campo_id' => $campos[array_rand($campos)],
                'objetivo_id' => $objetivos[array_rand($objetivos)],
                'actividad_id' => $actividades[array_rand($actividades)],
                'duracion' => 24,
                'inicio' => '2024-01-01',
                'fin' => '2025-12-31',
                'estado' => true,
                'disposicion' => 'Disposición 001/24',
                'resolucion' => 'Resolución 002/24',
                'pdf_disposicion' => json_encode(['disposicion_001.pdf']),
                'pdf_resolucion' => json_encode(['resolucion_002.pdf']),
                'presupuesto' => 500000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nro' => 102,
                'nombre' => 'Proyecto de Biotecnología',
                'resumen' => 'Desarrollo de nuevos cultivos resistentes a sequías.',
                'carrera_id' => $carrera->id,
                'campo_id' => $campos[array_rand($campos)],
                'objetivo_id' => $objetivos[array_rand($objetivos)],
                'actividad_id' => $actividades[array_rand($actividades)],
                'duracion' => 36,
                'inicio' => '2023-06-01',
                'fin' => '2026-05-31',
                'estado' => true,
                'disposicion' => 'Disposición 010/23',
                'resolucion' => 'Resolución 011/23',
                'pdf_disposicion' => json_encode(['disposicion_010.pdf']),
                'pdf_resolucion' => json_encode(['resolucion_011.pdf']),
                'presupuesto' => 750000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nro' => 103,
                'nombre' => 'Proyecto de Inteligencia Artificial',
                'resumen' => 'Aplicaciones de IA para análisis de datos científicos.',
                'carrera_id' => $carrera->id,
                'campo_id' => $campos[array_rand($campos)],
                'objetivo_id' => $objetivos[array_rand($objetivos)],
                'actividad_id' => $actividades[array_rand($actividades)],
                'duracion' => 18,
                'inicio' => '2024-03-01',
                'fin' => '2025-08-31',
                'estado' => false,
                'disposicion' => 'Disposición 020/24',
                'resolucion' => 'Resolución 021/24',
                'pdf_disposicion' => json_encode(['disposicion_020.pdf']),
                'pdf_resolucion' => json_encode(['resolucion_021.pdf']),
                'presupuesto' => 300000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nro' => 104,
                'nombre' => 'Proyecto de Salud Pública',
                'resumen' => 'Estudio epidemiológico en zonas rurales.',
                'carrera_id' => $carrera->id,
                'campo_id' => $campos[array_rand($campos)],
                'objetivo_id' => $objetivos[array_rand($objetivos)],
                'actividad_id' => $actividades[array_rand($actividades)],
                'duracion' => 12,
                'inicio' => '2023-09-01',
                'fin' => '2024-08-31',
                'estado' => true,
                'disposicion' => 'Disposición 030/23',
                'resolucion' => 'Resolución 031/23',
                'pdf_disposicion' => json_encode(['disposicion_030.pdf']),
                'pdf_resolucion' => json_encode(['resolucion_031.pdf']),
                'presupuesto' => 200000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nro' => 105,
                'nombre' => 'Proyecto de Educación Ambiental',
                'resumen' => 'Campañas de concientización en escuelas.',
                'carrera_id' => $carrera->id,
                'campo_id' => $campos[array_rand($campos)],
                'objetivo_id' => $objetivos[array_rand($objetivos)],
                'actividad_id' => $actividades[array_rand($actividades)],
                'duracion' => 6,
                'inicio' => '2024-05-01',
                'fin' => '2024-10-31',
                'estado' => false,
                'disposicion' => 'Disposición 040/24',
                'resolucion' => 'Resolución 041/24',
                'pdf_disposicion' => json_encode(['disposicion_040.pdf']),
                'pdf_resolucion' => json_encode(['resolucion_041.pdf']),
                'presupuesto' => 100000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
