<?php

namespace Database\Seeders;

use App\Models\Becario;
use App\Models\Carrera;
use App\Models\NivelAcademico;
use App\Models\Disciplina;
use App\Models\Campo;
use App\Models\Objetivo;
use Illuminate\Database\Seeder;

class BecariosSeeder extends Seeder
{
    public function run(): void
    {
        $carrera = Carrera::first();
        $nivelAcademico = NivelAcademico::first();
        $disciplina = Disciplina::first();
        $campo = Campo::first();
        $objetivo = Objetivo::first();

        Becario::insert([
            // BECARIO DE GRADO
            [
                'nombre' => 'Sofía',
                'apellido' => 'Ramírez',
                'dni' => '30111331',
                'cuil' => '27-30111331-5',
                'fecha_nac' => '2000-03-15',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Av. La Plata 222',
                'provincia' => 'Chaco',
                'email' => 'sofia.ramirez@example.com',
                'telefono' => '3624559988',
                'carrera_id' => $carrera->id,
                'nivel_academico_id' => null,
                'disciplina_id' => null,
                'campo_id' => null,
                'objetivo_id' => null,
                'titulo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // BECARIO DE POSGRADO
            [
                'nombre' => 'Julián',
                'apellido' => 'Martínez',
                'dni' => '30111332',
                'cuil' => '20-30111332-7',
                'fecha_nac' => '1992-07-20',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Calle 15 Nº 100',
                'provincia' => 'Corrientes',
                'email' => 'julian.martinez@example.com',
                'telefono' => '3624001234',
                'carrera_id' => null,
                'nivel_academico_id' => $nivelAcademico->id,
                'disciplina_id' => $disciplina->id,
                'campo_id' => $campo->id,
                'objetivo_id' => $objetivo->id,
                'titulo' => 'Lic. en Sociología',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // BECARIO DE GRADO
            [
                'nombre' => 'Camila',
                'apellido' => 'Torres',
                'dni' => '30111333',
                'cuil' => '27-30111333-6',
                'fecha_nac' => '2001-11-05',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Pasaje Los Andes 333',
                'provincia' => 'Formosa',
                'email' => 'camila.torres@example.com',
                'telefono' => '3624667789',
                'carrera_id' => $carrera->id,
                'nivel_academico_id' => null,
                'disciplina_id' => null,
                'campo_id' => null,
                'objetivo_id' => null,
                'titulo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // BECARIO DE POSGRADO
            [
                'nombre' => 'Matías',
                'apellido' => 'Sosa',
                'dni' => '30111334',
                'cuil' => '20-30111334-4',
                'fecha_nac' => '1990-05-10',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Ruta 11 KM 5',
                'provincia' => 'Misiones',
                'email' => 'matias.sosa@example.com',
                'telefono' => '3624556789',
                'carrera_id' => null,
                'nivel_academico_id' => $nivelAcademico->id,
                'disciplina_id' => $disciplina->id,
                'campo_id' => $campo->id,
                'objetivo_id' => $objetivo->id,
                'titulo' => 'Lic. en Ciencias Políticas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // BECARIO DE GRADO
            [
                'nombre' => 'Valentina',
                'apellido' => 'Gómez',
                'dni' => '30111335',
                'cuil' => '27-30111335-1',
                'fecha_nac' => '1999-12-22',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Barrio Norte, Mza A Casa 2',
                'provincia' => 'Chaco',
                'email' => 'valentina.gomez@example.com',
                'telefono' => '3624990011',
                'carrera_id' => $carrera->id,
                'nivel_academico_id' => null,
                'disciplina_id' => null,
                'campo_id' => null,
                'objetivo_id' => null,
                'titulo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
