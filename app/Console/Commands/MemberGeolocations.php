<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UpdateMemberGeolocation;
use App\Member;
use Illuminate\Console\Command;

class MemberGeolocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:geolocations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update geolocations for all relevant members';

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
        $members = Member::where('soort', '<>', 'oud')->where(function ($query) {
            $query->whereNull('geolocatie')->orWhereNotNull('geolocatie_error');
        })->get();

        $members->map(function ($member) {
            $this->info("Updating geolocation for member {$member->volnaam}");
            UpdateMemberGeolocation::dispatch($member);
        });

        return 0;
    }
}
