<?php

declare(strict_types=1);

namespace App\Services\ActionGenerator;

use App\Models\Action;
use App\Services\ActionGenerator\ValueObject\EventActionInput;

final class EventStraightFlushActionApplicator implements EventActionApplicator
{
    public const STRAIGHT_FLUSH_CODE = 'straight_flush';

    private const POINTS_STRAIGHT_FLUSH = 3;

    private const REQUIRED_NUMBER_OF_CAMP_TYPES = 5;

    private const CUT_OFF_DATE = '2014-09-01';

    public function apply(EventActionInput $input): void
    {
        $action = new Action();

        $action->points = self::POINTS_STRAIGHT_FLUSH;
        $action->description = 'Straight flush';
        $action->date = $input->getEvent()->datum_start;
        $action->code = self::STRAIGHT_FLUSH_CODE;

        $action->member()->associate($input->getMember());

        $action->save();
    }

    public function shouldApply(EventActionInput $input): bool
    {
        if ($input->getMember()->actions()->where('code', self::STRAIGHT_FLUSH_CODE)->exists()) {
            return false;
        }

        $codes = $input->getMember()->events()
            ->where('type', 'kamp')
            ->whereBetween('datum_start', [
                self::CUT_OFF_DATE,
                $input->getEvent()->datum_start->year . '-12-31',
            ])
            ->where('wissel', 0)
            ->selectRaw('SUBSTRING(code, 1, 1) as code')
            ->distinct()
            ->get('code')
            ->map(fn (object $data) => $data['code'])
            ->toArray();

        $codes = array_unique($codes);

        // Treat K and N camps the same; 'Kerst kamp' and Nieuwjaars kamp' are seen as the same type of camp
        if (in_array('K', $codes, true) && in_array('N', $codes, true)) {
            $codes = array_diff($codes, ['K']);
        }

        return ! (count($codes) < self::REQUIRED_NUMBER_OF_CAMP_TYPES);
    }
}
