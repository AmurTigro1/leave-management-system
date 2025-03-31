<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearlyHolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('yearly_holidays')->insert([
            ['name' => 'New Year\'s Day', 'date' => '2025-01-01', 'type' => 'regular', 'repeats_annually' => true],
            ['name' => 'Maundy Thursday', 'date' => '2025-04-17', 'type' => 'regular', 'repeats_annually' => false],
            ['name' => 'Good Friday', 'date' => '2025-04-18', 'type' => 'regular', 'repeats_annually' => false],
            ['name' => 'Araw ng Kagitingan', 'date' => '2025-04-09', 'type' => 'regular', 'repeats_annually' => true],
            ['name' => 'Labor Day', 'date' => '2025-05-01', 'type' => 'regular', 'repeats_annually' => true],
            ['name' => 'Independence Day', 'date' => '2025-06-12', 'type' => 'regular', 'repeats_annually' => true],
            ['name' => 'National Heroes Day', 'date' => '2025-08-25', 'type' => 'regular', 'repeats_annually' => false],
            ['name' => 'Bonifacio Day', 'date' => '2025-11-30', 'type' => 'regular', 'repeats_annually' => true],
            ['name' => 'Christmas Day', 'date' => '2025-12-25', 'type' => 'regular', 'repeats_annually' => true],
            ['name' => 'Rizal Day', 'date' => '2025-12-30', 'type' => 'regular', 'repeats_annually' => true],

            ['name' => 'Chinese New Year', 'date' => '2025-01-29', 'type' => 'special', 'repeats_annually' => false],
            ['name' => 'Black Saturday', 'date' => '2025-04-19', 'type' => 'special', 'repeats_annually' => false],
            ['name' => 'Ninoy Aquino Day', 'date' => '2025-08-21', 'type' => 'special', 'repeats_annually' => true],
            ['name' => 'All Saints\' Day', 'date' => '2025-11-01', 'type' => 'special', 'repeats_annually' => true],
            ['name' => 'All Souls\' Day', 'date' => '2025-11-02', 'type' => 'special', 'repeats_annually' => true],
            ['name' => 'Christmas Eve', 'date' => '2025-12-24', 'type' => 'special', 'repeats_annually' => true],
            ['name' => 'New Year\'s Eve', 'date' => '2025-12-31', 'type' => 'special', 'repeats_annually' => true],

            ['name' => 'EDSA People Power Revolution', 'date' => '2025-02-25', 'type' => 'national', 'repeats_annually' => true],
            ['name' => 'Eid al-Fitr', 'date' => '2025-03-31', 'type' => 'national', 'repeats_annually' => false], 
            ['name' => 'Eid al-Adha', 'date' => '2025-06-07', 'type' => 'national', 'repeats_annually' => false],
        ]);
    }
}
