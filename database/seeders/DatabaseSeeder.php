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
            'name' => 'Lucy',
            'first_name' => 'Lucyna',
            'middle_name' => 'David',
            'last_name' => 'Kushinada',
            'department' => 'DILG BOHOL',
            'birthday' => '2002-03-11',
            'email' => 'lucy@gmail.com',
            'password' => 'password',
            'role' => 'employee'
        ]);

        User::factory()->create([
            'name' => 'HR',
            'first_name' => 'HR fname',
            'middle_name' => 'HR mname',
            'last_name' => 'HR lname',
            'department' => 'DILG BOHOL',
            'birthday' => '2002-04-12',
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
            'birthday' => '2002-02-15',
            'email' => 'supervisor@gmail.com',
            'password' => 'password',
            'role' => 'supervisor',
        ]);
        
    }
}
