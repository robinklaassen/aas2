<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class ContactFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|regex:/[0-9]{10}/i',
            'message' => 'required|string',
            'recaptcha' => 'required|string',
        ];
    }
}
