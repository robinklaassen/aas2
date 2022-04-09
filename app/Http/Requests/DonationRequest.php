<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class DonationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string|nullable',
            'amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    public function amount(): float
    {
        return (float) $this->get('amount');
    }

    public function name(): ?string
    {
        return $this->get('name');
    }

    public function attributes()
    {
        return [
            'amount' => 'Bedrag',
            'name' => 'Naam',
        ];
    }
}
