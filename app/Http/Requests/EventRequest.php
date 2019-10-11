<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class EventRequest extends Request {

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
			'naam' => 'required',
			'code' => 'required',
			'type' => 'required',
			'datum_start' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
			'datum_eind' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
			'datum_voordag' => 'sometimes|nullable|regex:/\d{4}-\d{2}-\d{2}/',
			'prijs' => 'sometimes|nullable|numeric',
			'location_id' => 'required'
		];
	}

}
