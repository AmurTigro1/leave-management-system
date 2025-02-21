<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
class FetchHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store public holidays';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $countryCode = 'PH'; // Change this for different countries
        $year = now()->year;
        $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/{$countryCode}";

        $response = Http::get($url);
        if ($response->successful()) {
            $holidays = $response->json();
            
            foreach ($holidays as $holiday) {
                Holiday::updateOrCreate(
                    ['date' => $holiday['date']],
                    ['name' => $holiday['localName']]
                );
            }
            $this->info('Holidays updated successfully!');
        } else {
            $this->error('Failed to fetch holidays.');
        }
    
    }
}
