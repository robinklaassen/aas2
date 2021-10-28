<?php

declare(strict_types=1);

namespace App\Http\Requests;

class BulkDeclarationsRequest extends Request
{
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.amount' => 'required|numeric|gt:0',
            'data.*.date' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
            'data.*.declaration_type' => 'required|in:pay,gift,pay-biomeat',
            'data.*.description' => 'required|string',
            'data.*.file' => 'file',
        ];
    }

    public function attributes()
    {
        return [
            'data.*.description' => 'Omschrijving',
            'data.*.amount' => 'Bedrag',
            'data.*.date' => 'Datum',
            'data.*.declaration_type' => 'Type',
            'data.*.file' => 'Bestand',
        ];
    }

    public function messages()
    {
        return [
            'data.required' => 'Er moet wel iets ingevuld worden',
        ];
    }
}
