<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FinalizeEvents extends Command
{
    protected $signature = 'events:finalize
        {--event= : Finalize a specific event}
    ';

    protected $description = 'Finalize events';

    public function handle(): int
    {
        if (! $this->option('event')) {
            $eventToFinalize = Event::query()
                ->whereNull('finalized_at')
                ->where('datum_eind', '<', Carbon::now())
                ->get();
        } else {
            $eventToFinalize = [Event::findOrFail($this->option('event'))];
        }

        /** @var Event $event */
        foreach ($eventToFinalize as $event) {
            $this->output->info('Finalizing ' . $event->code);
            $event->finalize();
        }

        return 0;
    }
}
