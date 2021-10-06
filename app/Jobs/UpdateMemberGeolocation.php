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
            $this->member->geolocatie = $geolocation->toPoint();
        } catch (GeocoderException $e) {
            Log::warning("Exception when geocoding address for member {$this->member->volnaam}: {$e->getMessage()}");
            $this->member->geolocatie = null;  // So gets updated again on next attribute call
        } finally {
            $this->member->save();
        }
    }
}
