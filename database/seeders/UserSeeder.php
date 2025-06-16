<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
            'is_admin' => 1,
        ]);

        User::create([
            'name' => 'Lean',
            'email' => 'lean@gmail.com',
            'password' => 'lean1234',
            'is_admin' => 0,
        ]);
    }
}
