<?php

namespace App\Http\Requests;

class ParticipantRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'voornaam' => 'required',
            'achternaam' => 'required',
            'geboortedatum' => 'sometimes|regex:/\d{4}-\d{2}-\d{2}/',
            'geslacht' => 'sometimes',
            'adres' => 'sometimes',
            'postcode' => ['sometimes', 'regex:/\d{4}\s?[A-z]{2}/'],
            'plaats' => 'required',
            'telefoon_deelnemer' => 'sometimes|digits:10',
            'email_deelnemer' => 'sometimes|email',
            'telefoon_ouder_vast' => 'sometimes|digits:10',
            'telefoon_ouder_mobiel' => 'sometimes|digits:10',
            'email_ouder' => 'sometimes|email',
            'school' => 'sometimes',
            'niveau' => 'sometimes',
            'klas' => 'sometimes',
            'inkomen' => 'sometimes',
            'hoebij' => 'sometimes',
            'information_channel' => 'when_then_different:only-email,email_deelnemer,email_ouder'
            //'voorwaarden' => 'required',
            //'privacy' => 'required'
        ];
    }

    public function messages()
    {
        return [
            "information_channel.when_then_different" => "Om informatie enkel via email te ontvangen moeten twee verschillende mailadressen opgegeven worden, voor de ouder en de deelnemer."
        ];
    }
}
