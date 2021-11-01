<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Member;

class ReviewRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'bs-uren' => 'required|numeric',
            'bs-mening' => 'required',
            'bs-tevreden' => 'required',
            'bs-manier' => 'required',
            'bs-thema' => 'required',
            'leden' => 'required|array',
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
            'nogeens' => 'required',
        ];

        foreach ($this->input('leden', []) as $memberId) {
            $rules = array_merge($rules, [
                "stof-${memberId}" => 'required',
                "aandacht-${memberId}" => 'required',
                "mening-${memberId}" => 'required',
                "tevreden-${memberId}" => 'required',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
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
            'nogeens.required' => 'Geef aan of je nog eens op kamp zou willen.',
        ];

        foreach ($this->input('leden', []) as $memberId) {
            $naam = Member::findOrFail($memberId)->voornaam;
            $messages = array_merge($messages, [
                "stof-${memberId}.required" => "Geef aan hoe ${naam} de stof uitlegt.",
                "aandacht-${memberId}.required" => "Geef aan hoeveel aandacht je van ${naam} kreeg.",
                "mening-${memberId}.required" => "Geef je mening over het bijgespijkerd worden door ${naam}.",
                "tevreden-${memberId}.required" => "Geef aan of je tevreden bent over wat je met ${naam} hebt bereikt.",
            ]);
        }

        return $messages;
    }
}
