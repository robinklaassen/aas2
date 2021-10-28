<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

class AnonymizeGenerator implements AnonymizeGeneratorInterface
{
    private NameGeneratorInterface $nameGenerator;

    public function __construct(NameGeneratorInterface $nameGenerator)
    {
        $this->nameGenerator = $nameGenerator;
    }

    public function firstname(): string
    {
        return 'Anonieme';
    }

    public function surnamePrefix(): ?string
    {
        return null;
    }

    public function surname(): string
    {
        return $this->nameGenerator->name();
    }

    public function birthdate(): string
    {
        return '1900-01-01';
    }

    public function address(): string
    {
        return '';
    }

    public function zipcode(): string
    {
        return '';
    }

    public function telephone(): string
    {
        return '';
    }

    public function email(): string
    {
        return '';
    }

    public function city(): string
    {
        return '';
    }
}
