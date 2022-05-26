<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Models\Event;

final class CampMapData
{
    public int $id;

    public string $title;

    public string $verenigingsjaar;

    public array $latlng;

    public int $num_members;

    public int $num_participants;

    public function __construct(int $id, string $title, string $verenigingsjaar, array $latlng, int $num_members, int $num_participants)
    {
        $this->id = $id;
        $this->title = $title;
        $this->verenigingsjaar = $verenigingsjaar;
        $this->latlng = $latlng;
        $this->num_members = $num_members;
        $this->num_participants = $num_participants;
    }

    public static function fromEvent(Event $event): static
    {
        $loc = $event->location;
        return new self(
            $event->id,
            $event->full_title,
            $event->verenigingsjaar,
            [$loc->geolocatie->getLat(), $loc->geolocatie->GetLng()],
            $event->members()->wherePivot('wissel', 0)->count(),
            $event->participants()->count()
        );
    }
}
