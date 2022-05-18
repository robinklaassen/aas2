<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UpdateLocationGeolocation;
use App\Models\Location;
use Illuminate\Console\Command;

class LocationGeolocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:geolocations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update geolocations for all relevant locations';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locations = Location::whereNull('geolocatie')->orWhereNotNull('geolocatie_error')->get();

        $locations->map(function ($location) {
            $this->info("Updating geolocation for location {$location->naam}");
            UpdateLocationGeolocation::dispatch($location);
        });

        return 0;
    }
}
