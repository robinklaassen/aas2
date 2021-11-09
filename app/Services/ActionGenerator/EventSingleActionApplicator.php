<?php

declare(strict_types=1);

namespace App\Services\ActionGenerator;

use App\Models\Action;
use App\Services\ActionGenerator\ValueObject\EventActionInput;
use Carbon\Carbon;

final class EventSingleActionApplicator implements EventActionApplicator
{
    private const POINTS_TRAINING = 2;

    private const POINTS_CAMP_FULL = 3;

    private const POINTS_CAMP_PARTIAL = 1;

    private const CUTOFF_DATE = '2014-09-01';

    public function shouldApply(EventActionInput $input): bool
    {
        return ! $input->getEvent()->cancelled
            && in_array($input->getEvent()->type, ['kamp', 'training'], true)
            && $input->getEvent()->datum_start > self::CUTOFF_DATE
            && $input->getEvent()->datum_eind < Carbon::now()
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
            return self::POINTS_TRAINING;
        }

        if ($input->isWissel()) {
            return self::POINTS_CAMP_PARTIAL;
        }

        return self::POINTS_CAMP_FULL;
    }

    private function getCode(EventActionInput $input): string
    {
        return $input->getEvent()->code;
    }
}
