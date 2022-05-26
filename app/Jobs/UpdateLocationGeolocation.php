<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\GeocoderException;
use App\Models\Location;
use App\Services\Geocoder\GeocoderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateLocationGeolocation implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    use SerializesModels;

    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function handle(GeocoderInterface $geocoder)
    {
        try {
            $geolocation = $geocoder->geocode($this->location->volledigAdres);
            $this->location->geolocatie = $geolocation->toPoint();
            $this->location->geolocatie_error = null;
        } catch (GeocoderException $e) {
            Log::warning("Exception when geocoding address for location {$this->location->naam}: {$e->getMessage()}");
            $this->location->geolocatie_error = $e->getMessage();
        } finally {
            $this->location->save();
        }
    }
}
