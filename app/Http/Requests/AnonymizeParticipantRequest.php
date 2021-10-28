<?php

declare(strict_types=1);

namespace App\Http\Requests;

class AnonymizeParticipantRequest extends Request
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
            'participant' => 'required|array',
            'participant.*' => 'required|integer',
        ];
    }
}
