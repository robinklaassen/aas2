<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\WebsiteUpdater\WebsiteUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class UpdateWebsite implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    use SerializesModels;

    public function __construct()
    {
    }

    public function handle(WebsiteUpdater $updater): void
    {
        $updater->update();
    }
}
