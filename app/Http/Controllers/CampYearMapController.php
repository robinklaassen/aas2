<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\ValueObjects\CampMapData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CampYearMapController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $camps = Event::where('type', 'kamp')->ended()->notCancelled()
            ->whereHas('location', function (Builder $query) {
                return $query->whereNotNull('geolocatie');
            })
            ->orderBy('datum_start')
            ->get()->map(function ($c) {
                return CampMapData::fromEvent($c);
            })->values();

        return view('pages.camp-year-map', compact('camps'));
    }
}
