<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Employee',
            'email' => 'employee@gmail.com',
            'password' => '123456789',
            'role' => 'employee'
        ]);

        User::factory()->create([
            'name' => 'HR',
            'email' => 'hr@example.com',
            'password' => 'password',
            'role' => 'hr',
        ]);

        User::factory()->create([
            'name' => 'Supervisor',
            'email' => 'supervisor@example.com',
            'password' => 'password',
            'role' => 'supervisor',
        ]);
        
    }
}
