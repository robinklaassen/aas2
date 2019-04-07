<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ParticipantRequest extends Request {

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
			'geboortedatum' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
			'geslacht' => 'required',
			'adres' => 'required',
			'postcode' => ['required', 'regex:/\d{4}\s?[A-z]{2}/'],
			'plaats' => 'required',
			'telefoon_deelnemer' => 'sometimes|digits:10',
			'email_deelnemer' => 'sometimes|email',
			'telefoon_ouder_vast' => 'sometimes|digits:10',
			'telefoon_ouder_mobiel' => 'sometimes|digits:10',
			'email_ouder' => 'required|email',
			'school' => 'required',
			'niveau' => 'required',
			'klas' => 'required',
			'inkomen' => 'required',
			'hoebij' => 'required'
			//'voorwaarden' => 'required',
			//'privacy' => 'required'
		];
	}

}
