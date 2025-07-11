<?php

namespace Database\Seeders;

use App\Models\Investigador;
use App\Models\NivelAcademico;
use App\Models\Disciplina;
use App\Models\Campo;
use App\Models\Objetivo;
use App\Models\Cargo;
use App\Models\CategoriaInterna;
use App\Models\Incentivo;
use Illuminate\Database\Seeder;

class InvestigadorsSeeder extends Seeder
{
    public function run(): void
    {
        $nivelAcademico = NivelAcademico::first();
        $disciplina = Disciplina::first();
        $campo = Campo::first();
        $objetivo = Objetivo::first();
        $cargo = Cargo::first();
        $categoriaInterna = CategoriaInterna::first();
        $incentivo = Incentivo::first();

        Investigador::insert([
            [
                'nombre' => 'María',
                'apellido' => 'González',
                'dni' => '30111222',
                'cuil' => '27-30111222-3',
                'fecha_nac' => '1985-06-15',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Calle Falsa 123',
                'provincia' => 'Chaco',
                'email' => 'maria.gonzalez@example.com',
                'telefono' => '3624112233',
                'nivel_academico_id' => $nivelAcademico->id,
                'disciplina_id' => $disciplina->id,
                'campo_id' => $campo->id,
                'objetivo_id' => $objetivo->id,
                'titulo' => 'Lic. en Biología',
                'titulo_posgrado' => 'Doctorado en Ciencias Naturales',
                'cargo_id' => $cargo->id,
                'categoria_interna_id' => $categoriaInterna->id,
                'incentivo_id' => $incentivo->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'dni' => '30111223',
                'cuil' => '20-30111223-5',
                'fecha_nac' => '1978-02-10',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Av. Siempreviva 742',
                'provincia' => 'Corrientes',
                'email' => 'juan.perez@example.com',
                'telefono' => '3624556677',
                'nivel_academico_id' => $nivelAcademico->id,
                'disciplina_id' => $disciplina->id,
                'campo_id' => $campo->id,
                'objetivo_id' => $objetivo->id,
                'titulo' => 'Ingeniero Agrónomo',
                'titulo_posgrado' => 'Maestría en Producción Vegetal',
                'cargo_id' => $cargo->id,
                'categoria_interna_id' => $categoriaInterna->id,
                'incentivo_id' => $incentivo->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lucía',
                'apellido' => 'Fernández',
                'dni' => '30111224',
                'cuil' => '27-30111224-4',
                'fecha_nac' => '1990-11-20',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Calle 12 Nº 456',
                'provincia' => 'Formosa',
                'email' => 'lucia.fernandez@example.com',
                'telefono' => '3624778899',
                'nivel_academico_id' => $nivelAcademico->id,
                'disciplina_id' => $disciplina->id,
                'campo_id' => $campo->id,
                'objetivo_id' => $objetivo->id,
                'titulo' => 'Lic. en Química',
                'titulo_posgrado' => 'Doctorado en Ciencias Químicas',
                'cargo_id' => $cargo->id,
                'categoria_interna_id' => $categoriaInterna->id,
                'incentivo_id' => $incentivo->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'López',
                'dni' => '30111225',
                'cuil' => '20-30111225-9',
                'fecha_nac' => '1980-03-01',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'Ruta 11 Km 10',
                'provincia' => 'Chaco',
                'email' => 'carlos.lopez@example.com',
                'telefono' => '3624667788',
                'nivel_academico_id' => $nivelAcademico->id,
                'disciplina_id' => $disciplina->id,
                'campo_id' => $campo->id,
                'objetivo_id' => $objetivo->id,
                'titulo' => 'Lic. en Física',
                'titulo_posgrado' => 'Maestría en Energías Renovables',
                'cargo_id' => $cargo->id,
                'categoria_interna_id' => $categoriaInterna->id,
                'incentivo_id' => $incentivo->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'dni' => '30111226',
                'cuil' => '27-30111226-2',
                'fecha_nac' => '1988-09-05',
                'lugar_nac' => 'Saenz Penia',
                'domicilio' => 'B° Centro, Casa 3',
                'provincia' => 'Misiones',
                'email' => 'ana.martinez@example.com',
                'telefono' => '3624001122',
                'nivel_academico_id' => $nivelAcademico->id,
                'disciplina_id' => $disciplina->id,
                'campo_id' => $campo->id,
                'objetivo_id' => $objetivo->id,
                'titulo' => 'Lic. en Matemática',
                'titulo_posgrado' => 'Doctorado en Estadística Aplicada',
                'cargo_id' => $cargo->id,
                'categoria_interna_id' => $categoriaInterna->id,
                'incentivo_id' => $incentivo->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
