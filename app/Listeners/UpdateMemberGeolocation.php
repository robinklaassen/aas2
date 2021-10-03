<?php

namespace App\Listeners;

use App\Events\MemberUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Geocoder\GeocoderInterface;
use Illuminate\Http\Client\RequestException;

class UpdateMemberGeolocation
{
    private $geocoder;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * Handle the event.
     *
     * @param  MemberUpdated  $event
     * @return void
     */
    public function handle(MemberUpdated $event)
    {
        // TODO can we check if address property was changed?
        
        $member = $event->member;
        try {
			$geolocation = $this->geocoder->geocode($member->volledigAdres);
		} catch (RequestException $e) {
			return;  // TODO log errors?
		}

        $member->geolocatie = $geolocation->toPoint();
        $member->saveQuietly();  // Prevent dispatching new events in an endless loop
    }
}
