<?php

namespace App\Services\Chart;

use App\Event;
use App\Member;

interface ChartServiceInterface
{
    public function prepareEventReviewChart(Event $event, string $question, ?Member $member = null): void;
}