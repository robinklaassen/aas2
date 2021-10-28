<?php

declare(strict_types=1);

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

final class EditEventMemberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wissel' => 'required|boolean',
            'wissel_datum_start' => 'required_if:wissel,1|nullable|date',
            'wissel_datum_eind' => 'required_if:wissel,1|nullable|date|after:wissel_datum_start',
        ];
    }
}
