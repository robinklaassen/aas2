<?php

declare(strict_types=1);

namespace App\Http\Requests;

class LocationRequest extends Request
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
            'adres' => 'required',
            'postcode' => 'required',
            'plaats' => 'required',
            'email' => 'sometimes|email',
        ];
    }
}
