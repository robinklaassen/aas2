<?php

declare(strict_types=1);

namespace App\Http\Requests;

class ActionRequest extends Request
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
            'date' => 'date|required',
            'member_id' => 'required',
            'description' => 'required',
            'points' => 'required|min:1',
        ];
    }
}
