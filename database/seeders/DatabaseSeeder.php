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
            'first_name' => 'Employee fname',
            'middle_name' => 'Employee mname',
            'last_name' => 'Employee lname',
            'department' => 'DILG BOHOL',
            'email' => 'employee@gmail.com',
            'password' => 'password',
            'role' => 'employee'
        ]);

        User::factory()->create([
            'name' => 'HR',
            'first_name' => 'HR fname',
            'middle_name' => 'HR mname',
            'last_name' => 'HR lname',
            'department' => 'DILG BOHOL',
            'email' => 'hr@gmail.com',
            'password' => 'password',
            'role' => 'hr',
        ]);

        User::factory()->create([
            'name' => 'Supervisor',
            'first_name' => 'Supervisor fname',
            'middle_name' => 'Supervisor mname',
            'last_name' => 'Supervisor lname',
            'department' => 'DILG BOHOL',
            'email' => 'supervisor@gmail.com',
            'password' => 'password',
            'role' => 'supervisor',
        ]);
        
    }
}
