<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Models\Event;
use App\Models\Location;

final class CampMapData
{
    public int $id;

    public string $title;

    public string $verenigingsjaar;

    public array $latlng;

    public int $numMembers;

    public int $numParticipants;

    public int $size;

    public function __construct(int $id, string $title, string $verenigingsjaar, array $latlng, int $numMembers, int $numParticipants)
    {
        $this->id = $id;
        $this->title = $title;
        $this->verenigingsjaar = $verenigingsjaar;
        $this->latlng = $latlng;
        $this->numMembers = $numMembers;
        $this->numParticipants = $numParticipants;

        $this->size = $numMembers + $numParticipants;
    }

    public static function fromEvent(Event $event): static
    {
        /** @var Location $loc */
        $loc = $event->location;
        return new self(
            $event->id,
            $event->full_title,
            $event->verenigingsjaar,
            [$loc->geolocatie->latitude, $loc->geolocatie->longitude],
            $event->members()->wherePivot('wissel', 0)->count(),
            $event->participants()->count()
        );
    }
}
