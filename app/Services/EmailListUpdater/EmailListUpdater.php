<?php

declare(strict_types=1);

namespace App\Services\EmailListUpdater;

interface EmailListUpdater
{
    public function update(): void;
}
