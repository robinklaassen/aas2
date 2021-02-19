<?php

namespace App\ViewModels\Api;

use App\Location;
use App\ViewModels\ViewModel;

class PublicEventLocation extends ViewModel
{
    protected array $visible = ['name', 'address', 'zipcode', 'city', 'phone', 'website'];

    private Location $location;

    private function __construct(Location $location)
    {
        $this->location = $location;
    }

    public static function fromLocation(Location $location): self
    {
        return new self($location);
    }

    public function getName(): string
    {
        return $this->location->naam;
    }

    public function getAddress(): string
    {
        return $this->location->adres;
    }

    public function getZipcode(): string
    {
        return $this->location->postcode;
    }

    public function getCity(): string
    {
        return $this->location->plaats;
    }

    public function getPhone(): string
    {
        return $this->location->telefoon;
    }

    public function getWebsite(): string
    {
        return $this->location->website;
    }
}
