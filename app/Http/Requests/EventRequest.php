<?php

declare(strict_types=1);

namespace App\Http\Requests;

class EventRequest extends Request
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
            'naam' => 'required',
            'code' => 'required',
            'type' => 'required',
            'datum_start' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
            'datum_eind' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
            'datum_voordag' => 'bail|required_if:type,kamp|nullable|regex:/\d{4}-\d{2}-\d{2}/',
            'prijs' => 'sometimes|nullable|numeric',
            'location_id' => 'required',
            'tijd_start' => 'required|regex:/\d{2}:\d{2}/',
            'tijd_eind' => 'required|regex:/\d{2}:\d{2}/',
        ];
    }
}
