<?php

declare(strict_types=1);

namespace App\Services\ActionGenerator;

use App\Models\Action;
use App\Services\ActionGenerator\ValueObject\EventActionInput;

final class EventSingleActionApplicator implements EventActionApplicator
{
    public function shouldApply(EventActionInput $input): bool
    {
        return ! $input->getEvent()->cancelled
            && $input->getMember()->actions()->where('code', $this->getCode($input))->doesntExist();
    }

    public function apply(EventActionInput $input): void
    {
        $action = new Action();

        $action->points = $this->getEventActionPoints($input);
        $action->description = $this->getEventActionDescription($input);
        $action->date = $input->getEvent()->datum_start;
        $action->code = $input->getEvent()->code;
        $action->member()->associate($input->getMember());

        $action->save();
    }

    private function getEventActionDescription(EventActionInput $input): string
    {
        return $input->getEvent()->naam . ' '
            . $input->getEvent()->datum_start->format('Y')
            . ($input->isWissel() ? ' (w)' : '');
    }

    private function getEventActionPoints(EventActionInput $input): int
    {
        if ($input->getEvent()->type === 'training') {
            return 2;
        }

        if ($input->isWissel()) {
            return 1;
        }

        return 3;
    }

    private function getCode(EventActionInput $input): string
    {
        return $input->getEvent()->code;
    }
}
