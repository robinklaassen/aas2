<?php

namespace App\Jobs;

use App\Member;
use App\Exceptions\GeocoderException;
use App\Services\Geocoder\GeocoderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class UpdateMemberGeolocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $member;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GeocoderInterface $geocoder)
    {
        try {
            $geolocation = $geocoder->geocode($this->member->volledigAdres);
        } catch (GeocoderException $e) {
            Log::warning("Exception when geocoding address for member {$this->member->volnaam}: {$e->getMessage()}");
            // TODO maybe set geolocatie to null on failure? because otherwise it won't be updated again if it's an old address for example
            return;
        }

        $this->member->geolocatie = $geolocation->toPoint();
        $this->member->saveQuietly();  // TODO could be regular save() if event only gets dispatched on address property changes
    }
}
