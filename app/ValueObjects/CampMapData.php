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

    public int $size;

    public function __construct(int $id, string $title, string $verenigingsjaar, array $latlng, int $size)
    {
        $this->id = $id;
        $this->title = $title;
        $this->verenigingsjaar = $verenigingsjaar;
        $this->latlng = $latlng;
        $this->size = $size;
    }

    public static function fromEvent(Event $event): static
    {
        $loc = $event->location;
        return new self(
            $event->id,
            $event->full_title,
            $event->verenigingsjaar,
            [$loc->geolocatie->getLat(), $loc->geolocatie->GetLng()],
            $event->members()->count() + $event->participants()->count()
        );
    }
}
