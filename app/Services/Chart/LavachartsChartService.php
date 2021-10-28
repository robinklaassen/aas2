<?php

declare(strict_types=1);

namespace App\Services\Chart;

use App\Event;
use App\Member;
use Illuminate\Support\Facades\DB;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class LavachartsChartService implements ChartServiceInterface
{
    private const ANSWER_OPTIONS = [
        'bs-mening' => [
            1 => 'Te weinig',
            2 => 'Voldoende',
            3 => 'Te veel',
        ],
        'bs-tevreden' => [
            1 => 'Erg ontevreden',
            2 => 'Een beetje ontevreden',
            3 => 'Een beetje tevreden',
            4 => 'Erg tevreden',
        ],
        'bs-manier' => [
            0 => 'Nee',
            1 => 'Ja',
        ],
        'bs-thema' => [
            0 => 'Nee',
            1 => 'Ja',
        ],
        'slaaptijd' => [
            1 => 'Veel te weinig',
            2 => 'Weinig',
            3 => 'Genoeg',
            4 => 'Meer dan genoeg',
        ],
        'kamplengte' => [
            1 => 'Veel te kort',
            2 => 'Te kort',
            3 => 'Precies goed',
            4 => 'Te lang',
            5 => 'Veel te lang',
        ],
        'kh-slaap' => [
            1 => 'Slecht',
            2 => 'Onvoldoende',
            3 => 'Voldoende',
            4 => 'Goed',
        ],
        'kh-bijspijker' => [
            1 => 'Slecht',
            2 => 'Onvoldoende',
            3 => 'Voldoende',
            4 => 'Goed',
        ],
        'kh-geheel' => [
            1 => 'Slecht',
            2 => 'Onvoldoende',
            3 => 'Voldoende',
            4 => 'Goed',
        ],
        'stof' => [
            1 => 'Zeer slecht',
            2 => 'Slecht',
            3 => 'Gewoon',
            4 => 'Goed',
            5 => 'Zeer goed',
        ],
        'aandacht' => [
            1 => 'Te weinig',
            2 => 'Weinig',
            3 => 'Voldoende',
            4 => 'Veel',
        ],
        'mening' => [
            1 => 'Zeer vervelend',
            2 => 'Vervelend',
            3 => 'Gewoon',
            4 => 'Prettig',
            5 => 'Zeer prettig',
        ],
        'tevreden' => [
            1 => 'Erg ontevreden',
            2 => 'Een beetje ontevreden',
            3 => 'Een beetje tevreden',
            4 => 'Erg tevreden',
        ],
    ];

    private const BAR_CHART_OPTIONS = [
        'width' => '100%',
        'height' => '250',
        'chartArea' => [
            'top' => 25,
            'left' => '25%',
            'width' => '70%',
        ],
        'fontSize' => 14,
        'hAxis' => [
            'minValue' => 0,
            'gridlines' => [
                'count' => -1,
            ],
        ],
        'legend' => [
            'position' => 'none',
        ],
    ];

    public function prepareEventReviewChart(Event $event, string $question, ?Member $member = null): void
    {
        $dt = Lava::DataTable();
        $dt->addStringColumn('Optie');
        $dt->addNumberColumn('Aantal');

        $entity = $member ?? $event;
        $q = $entity->reviews()
            ->select($question, DB::raw('count(*) as total'))
            ->groupBy($question)
            ->pluck('total', $question)->toArray();

        foreach (self::ANSWER_OPTIONS[$question] as $n => $t) {
            if (array_key_exists($n, $q)) {
                $dt->addRow([$t, $q[$n]]);
            } else {
                $dt->addRow([$t, 0]);
            }
        }

        Lava::BarChart($question, $dt, self::BAR_CHART_OPTIONS);
    }
}
