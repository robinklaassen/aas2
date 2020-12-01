<?php

namespace App\Services\Anonymize;

use Faker\Generator;
use FakerProviderAnimals\Animals;

class AnonymizeGenerator implements AnonymizeGeneratorInterface
{
    /** @var Generator  */
    private $faker;

    public function __construct()
    {
        $this->faker = new Generator();
        $this->faker->addProvider(new Animals($this->faker));
    }

    public function firstname(): string
    {
        return "Anonymous";
    }

    public function surnamePrefix(): ?string
    {
        return null;
    }

    public function surname(): string
    {
        return $this->faker->animal();
    }

    public function birthdate(): string
    {
        return "1900-01-01";
    }

    public function address(): string
    {
        return "";
    }

    public function zipcode(): string
    {
        return "";
    }

    public function telephone(): string
    {
        return "";
    }

    public function email(): string
    {
        return "";
    }

    public function city(): string
    {
        return "";
    }
}
