<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HRSupervisor;

class HrSupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HRSupervisor::factory()->create([
            'supervisor_name' => 'Jerome G. Gonzales',
            'hr_name' => 'Mylove C. Flood',
            'supervisor_signature' => '',
            'hr_signature' => '',
        ]);    }
}
