<?php

declare(strict_types=1);

namespace App\Http\Requests;

class MemberRequest extends Request
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
            'geboortedatum' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
            'geslacht' => 'required',
            'adres' => 'required',
            'postcode' => ['required', 'regex:/\d{4}\s?[A-z]{2}/'],
            'plaats' => 'required',
            'telefoon' => 'required|digits:10',
            'email' => 'required|email',
            'studie' => 'required',
            'afgestudeerd' => 'required|boolean',
            'email_anderwijs' => 'nullable|email',
            'hoebij' => 'required',
        ];
    }
}
