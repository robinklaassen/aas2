<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\GeocoderException;
use App\Models\Member;
use App\Services\Geocoder\GeocoderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateMemberGeolocation implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    use SerializesModels;

    private $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function handle(GeocoderInterface $geocoder)
    {
        try {
            $geolocation = $geocoder->geocode($this->member->volledigAdres);
            $this->member->geolocatie = $geolocation->toPoint();
            $this->member->geolocatie_error = null;
        } catch (GeocoderException $e) {
            Log::warning("Exception when geocoding address for member {$this->member->volnaam}: {$e->getMessage()}");
            $this->member->geolocatie_error = $e->getMessage();
        } finally {
            $this->member->save();
        }
    }
}
