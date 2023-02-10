<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\EmailListUpdater\EmailListUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class UpdateEmailListSubscriptions implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    use SerializesModels;

    public function __construct(
    ) {
    }

    public function handle(EmailListUpdater $emailListUpdater): void
    {
        $emailListUpdater->update();
    }
}
