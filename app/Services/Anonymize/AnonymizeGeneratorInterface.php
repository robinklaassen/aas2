<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

interface AnonymizeGeneratorInterface
{
    public function firstname(): string;

    public function surnamePrefix(): ?string;

    public function surname(): string;

    public function birthdate(): string;

    public function address(): string;

    public function zipcode(): string;

    public function telephone(): string;

    public function email(): string;

    public function city(): string;
}
