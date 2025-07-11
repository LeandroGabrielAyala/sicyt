<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdscriptosSeeder extends Seeder
{
    public function run(): void
    {
        // Tomamos IDs de carreras para relaciones
        $carreras = DB::table('carreras')->pluck('id')->toArray();

        if (empty($carreras)) {
            $this->command->warn('No hay datos en la tabla carreras para ejecutar el seeder de adscriptos.');
            return;
        }

        DB::table('adscriptos')->insert([
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'dni' => '12345678',
                'cuil' => '20-12345678-9',
                'fecha_nac' => '1985-04-10',
                'lugar_nac' => 'Buenos Aires',
                'domicilio' => 'Calle Falsa 123',
                'provincia' => 'Buenos Aires',
                'codigo' => 'C001',
                'email' => 'juan.perez@example.com',
                'telefono' => '1234567890',
                'carrera_id' => $carreras[array_rand($carreras)],
                'titulo_id' => $carreras[array_rand($carreras)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'María',
                'apellido' => 'Gómez',
                'dni' => '23456789',
                'cuil' => '27-23456789-8',
                'fecha_nac' => '1990-11-22',
                'lugar_nac' => 'Córdoba',
                'domicilio' => 'Av. Siempre Viva 742',
                'provincia' => 'Córdoba',
                'codigo' => 'C002',
                'email' => 'maria.gomez@example.com',
                'telefono' => '0987654321',
                'carrera_id' => $carreras[array_rand($carreras)],
                'titulo_id' => $carreras[array_rand($carreras)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Luis',
                'apellido' => 'Fernández',
                'dni' => '34567890',
                'cuil' => '23-34567890-7',
                'fecha_nac' => '1980-07-15',
                'lugar_nac' => 'Rosario',
                'domicilio' => 'Calle Luna 456',
                'provincia' => 'Santa Fe',
                'codigo' => 'C003',
                'email' => 'luis.fernandez@example.com',
                'telefono' => '1122334455',
                'carrera_id' => $carreras[array_rand($carreras)],
                'titulo_id' => $carreras[array_rand($carreras)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'dni' => '45678901',
                'cuil' => '24-45678901-6',
                'fecha_nac' => '1995-02-28',
                'lugar_nac' => 'Mendoza',
                'domicilio' => 'Calle Sol 789',
                'provincia' => 'Mendoza',
                'codigo' => 'C004',
                'email' => 'ana.martinez@example.com',
                'telefono' => '5566778899',
                'carrera_id' => $carreras[array_rand($carreras)],
                'titulo_id' => $carreras[array_rand($carreras)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez',
                'dni' => '56789012',
                'cuil' => '25-56789012-5',
                'fecha_nac' => '1978-10-05',
                'lugar_nac' => 'Salta',
                'domicilio' => 'Av. Libertad 101',
                'provincia' => 'Salta',
                'codigo' => 'C005',
                'email' => 'carlos.rodriguez@example.com',
                'telefono' => '6677889900',
                'carrera_id' => $carreras[array_rand($carreras)],
                'titulo_id' => $carreras[array_rand($carreras)],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
