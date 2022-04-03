<?php

declare(strict_types=1);

namespace App\Http\Requests;

final class DonationRequest extends Request
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
}
