<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ActividadSeeder::class,
            CampoSeeder::class,
            ObjetivoSeeder::class,
            CargoSeeder::class,
            DisciplinaSeeder::class,
            FuncionSeeder::class,
            IncentivoSeeder::class,
            InternaSeeder::class,
            NivelAcaSeeder::class,
            TipoBecaSeeder::class,
            CarreraSeeder::class,
            InvestigadorsSeeder::class,
            BecariosSeeder::class,
            ConvocatoriaBecasSeeder::class,
            ProyectosSeeder::class,
            BecarioProyectoSeeder::class,
            AdscriptosSeeder::class
        ]);
    }
}
