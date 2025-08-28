<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear rol Admin si no existe
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Crear usuario Admin
        $user1 = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // Hashear contraseña
            'is_admin' => 1,
        ]);

        // Asignar rol Admin al usuario
        $user1->assignRole($role);

        // Crear otro usuario
        User::create([
            'name' => 'Lean',
            'email' => 'lean@gmail.com',
            'password' => Hash::make('lean1234'), // Hashear contraseña
            // 'is_admin' => 0,
        ]);
        User::create([
            'name' => 'Vanina',
            'email' => 'vanina@gmail.com',
            'password' => Hash::make('vanina1234'), // Hashear contraseña
            'is_admin' => 0,
        ]);
        User::create([
            'name' => 'Fabiola',
            'email' => 'fabiola@gmail.com',
            'password' => Hash::make('fabiola1234'), // Hashear contraseña
            'is_admin' => 0,
        ]);
        User::create([
            'name' => 'Mara',
            'email' => 'mara@gmail.com',
            'password' => Hash::make('mara1234'), // Hashear contraseña
            'is_admin' => 0,
        ]);
        User::create([
            'name' => 'Ricardo',
            'email' => 'ricardo@gmail.com',
            'password' => Hash::make('ricardo1234'), // Hashear contraseña
            'is_admin' => 0,
        ]);
    }
}
