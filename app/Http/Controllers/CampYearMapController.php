<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
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
                $loc = $c->location;
                return [
                    'id' => $c->id,
                    'titel' => $c->full_title,
                    'verenigingsjaar' => $c->verenigingsjaar,
                    'latlng' => [$loc->geolocatie->getLat(), $loc->geolocatie->getLng()],
                    'size' => $c->members()->count() + $c->participants()->count(),
                ];
            })->values();

        return view('pages.camp-year-map', compact('camps'));
    }
}
