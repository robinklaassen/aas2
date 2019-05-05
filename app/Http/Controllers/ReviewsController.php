<?php namespace App\Http\Controllers;

use App\Review;
use App\Member;
use App\Event;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ReviewsController extends Controller {
	
	# Display review form
	public function review(Event $event) {

		// No access if the given id is not a camp
		if ($event->type != 'kamp') {
			return redirect('/');
		}
		
		// No access outside of a useful time period around the camp
		$now = new Carbon();
		if (($now < $event->datum_voordag->subDays(15)) || ($event->datum_eind->addDays(15) < $now) ) {
			return redirect('/');
		}
		
		$members = $event->members()->orderBy('voornaam')->get();
		
		// List of possible camps to choose from
		$potCampList = [
			"herfst" 		=> "Herfstkamp (weekend in de herfstvakantie)",
			"winter" 		=> "Winterkamp (halve week voor Kerst of na Oud en Nieuw)",
			"voorjaar" 		=> "Voorjaarskamp (weekend in de voorjaarsvakantie)",
			"paas" 			=> "Paaskamp (lang weekend met Pasen)",
			"mei" 			=> "Meikamp (week in de meivakantie, vlak voor de eindexamens)",
			"hemelvaart" 	=> "Hemelvaartskamp (lang weekend met Hemelvaart, vlak voor de laatste toetsweek van niet-eindexamenklassen)",
			"zomer" 		=> "Zomerkamp (week in de zomervakantie)"
		];
		
		return view('reviews.form', compact('event', 'members', 'potCampList'));
	}
	
	# Process survey form
	public function reviewPost(Event $event, Request $request) {
		
		// Error messages
		$messages = [
			'bs-uren.required' => 'Vul het gemiddeld aantal bijspijkeruren per dag in.',
			'bs-mening.required' => 'Geef je mening over het aantal bijspijkeruren.',
			'bs-tevreden.required' => 'Geef aan of je tevreden bent wat je qua bijspijkeren hebt bereikt.',
			'bs-manier.required' => 'Geef aan of je de stof op een andere manier hebt uigelegd gekregen of niet.',
			'bs-thema.required' => 'Geef aan of je themablokken gehad hebt of niet.',
			'leden.required' => 'Geef aan bij welke leiding je blokjes gehad hebt.',
			'kh-slaap.required' => 'Geef aan wat je vond van de slaapruimtes in het kamphuis.',
			'kh-bijspijker.required' => 'Geef aan wat je vond van de bijspijkerruimtes in het kamphuis.',
			'kh-geheel.required' => 'Geef aan wat je vond van het kamphuis als geheel.',
			'leidingploeg.required' => 'Geef aan wat je vond van de leidingploeg.',
			'slaaptijd.required' => 'Geef aan of je voldoende tijd had om te slapen.',
			'kamplengte.required' => 'Geef aan wat je van de kamplengte vond.',
			'eten.required' => 'Geef aan wat je vond van het eten.',
			'avond-leukst.required' => 'Geef aan wat je het leukste avondspel vond.',
			'avond-minst.required' => 'Geef aan wat je het minst leuke avondspel vond.',
			'allerleukst.required' => 'Geef aan wat het allerleukste van dit kamp was.',
			'allervervelendst.required' => 'Geef aan wat het allervervelendst van dit kamp was.',
			'cijfer.required' => 'Geef een cijfer voor dit kamp.',
			'nogeens.required' => 'Geef aan of je nog eens op kamp zou willen.'
		];
		
		foreach ($event->members()->get() as $m) {
			$messages['stof-'.$m->id.'.required'] = 'Geef aan hoe ' . $m->voornaam . ' de stof uitlegt.';
			$messages['aandacht-'.$m->id.'.required'] = 'Geef aan hoeveel aandacht je van ' . $m->voornaam . ' kreeg.';
			$messages['mening-'.$m->id.'.required'] = 'Geef je mening over het bijgespijkerd worden door ' . $m->voornaam . '.';
			$messages['tevreden-'.$m->id.'.required'] = 'Geef aan of je tevreden bent over wat je met ' . $m->voornaam . ' hebt bereikt.';
		}
		
		// Validate
		$validator = \Validator::make($request->all(), [
			'bs-uren' => 'required|numeric',
			'bs-mening' => 'required',
			'bs-tevreden' => 'required',
			'bs-manier' => 'required',
			'bs-thema' => 'required',
			'leden' => 'required',
			'kh-slaap' => 'required',
			'kh-bijspijker' => 'required',
			'kh-geheel' => 'required',
			'leidingploeg' => 'required',
			'slaaptijd' => 'required',
			'kamplengte' => 'required',
			'eten' => 'required',
			'avond-leukst' => 'required',
			'avond-minst' => 'required',
			'allerleukst' => 'required',
			'allervervelendst' => 'required',
			'cijfer' => 'required|numeric',
			'nogeens' => 'required'
		], $messages);
		
		if ($request->leden != null) {
			foreach ($event->members()->get() as $m) {
				$fields = [
					'stof-'.$m->id,
					'aandacht-'.$m->id,
					'mening-'.$m->id,
					'tevreden-'.$m->id
				];
				
				$validator->sometimes($fields, 'required', function ($input) use ($m) {
					return in_array($m->id, $input->leden);
				});
			}
		}
		
		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->withErrors($validator->errors());
		}
		
		// Implode camp choice
		$choice_array = $request->kampkeuze;

		if ($choice_array !== null) {
			$k = array_search("0", $choice_array);
			if ($k !== FALSE) {
				$choice_array[$k] = $request->kampkeuze_anders;
			}
	
			$kampkeuze_string = implode(", ", $choice_array);
		} else {
			$kampkeuze_string = "";
		}

		$request->merge(array('kampkeuze' => $kampkeuze_string));
		
		// Store
		$review = new Review($request->only('bs-uren', 'bs-mening', 'bs-tevreden', 'bs-manier', 'bs-manier-mening', 'bs-thema', 'bs-thema-wat', 'kh-slaap', 'kh-slaap-wrm', 'kh-bijspijker', 'kh-bijspijker-wrm', 'kh-geheel', 'kh-geheel-wrm', 'leidingploeg', 'slaaptijd', 'slaaptijd-hoe', 'kamplengte', 'kamplengte-wrm', 'eten', 'avond-leukst', 'avond-minst', 'allerleukst', 'allervervelendst', 'cijfer', 'nogeens', 'kampkeuze',  'tip', 'verder'));
		
		$review = $event->reviews()->save($review);
		
		foreach ($request->leden as $m_id) {
			$review->members()->attach([$m_id => [
				'stof' => $request->{'stof-'.$m_id},
				'aandacht' => $request->{'aandacht-'.$m_id},
				'tevreden' => $request->{'tevreden-'.$m_id},
				'mening' => $request->{'mening-'.$m_id},
				'bericht' => $request->{'bericht-'.$m_id}
			]]);
		}
		
		return view('reviews.thanks');
	}
	
}