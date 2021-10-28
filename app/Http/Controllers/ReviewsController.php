<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\ReviewRequest;
use App\Review;
use Carbon\Carbon;

class ReviewsController extends Controller
{
    // Display review form
    public function review(Event $event)
    {

        // No access if the given id is not a camp
        if ($event->type !== 'kamp') {
            return redirect('/');
        }

        // No live access outside of a useful time period around the camp
        $now = new Carbon();
        if ((env('APP_ENV') !== 'local') && (($now < $event->datum_voordag->subDays(15)) || ($event->datum_eind->addDays(15) < $now))) {
            return redirect('/');
        }

        $members = $event->members()->orderBy('voornaam')->get();

        return view('reviews.form', compact('event', 'members'));
    }

    // Process survey form
    public function reviewPost(ReviewRequest $request, Event $event)
    {
        // Implode camp choice
        $choice_array = $request->kampkeuze;

        if ($choice_array !== null) {
            $k = array_search('0', $choice_array, true);
            if ($k !== false) {
                $choice_array[$k] = $request->kampkeuze_anders;
            }

            $kampkeuze_string = implode(', ', $choice_array);
        } else {
            $kampkeuze_string = '';
        }

        $request->merge([
            'kampkeuze' => $kampkeuze_string,
        ]);

        // Store
        $review = new Review($request->only('bs-uren', 'bs-mening', 'bs-tevreden', 'bs-manier', 'bs-manier-mening', 'bs-thema', 'bs-thema-wat', 'kh-slaap', 'kh-slaap-wrm', 'kh-bijspijker', 'kh-bijspijker-wrm', 'kh-geheel', 'kh-geheel-wrm', 'leidingploeg', 'slaaptijd', 'slaaptijd-hoe', 'kamplengte', 'kamplengte-wrm', 'eten', 'avond-leukst', 'avond-minst', 'allerleukst', 'allervervelendst', 'cijfer', 'nogeens', 'kampkeuze', 'tip', 'verder'));

        $review = $event->reviews()->save($review);

        foreach ($request->leden as $m_id) {
            $review->members()->attach([
                $m_id => [
                    'stof' => $request->{'stof-' . $m_id},
                    'aandacht' => $request->{'aandacht-' . $m_id},
                    'tevreden' => $request->{'tevreden-' . $m_id},
                    'mening' => $request->{'mening-' . $m_id},
                    'bericht' => $request->{'bericht-' . $m_id},
                ],
            ]);
        }

        return view('reviews.thanks');
    }
}
