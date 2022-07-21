<?php

declare(strict_types=1);

namespace App\Services\WebsiteUpdater;

interface WebsiteUpdater
{
    public function update(): void;
}
