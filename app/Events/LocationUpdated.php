<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Location;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated
{
    use Dispatchable;

    use InteractsWithSockets;

    use SerializesModels;

    public $location;

    /**
     * Create a new event instance.
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }
}
