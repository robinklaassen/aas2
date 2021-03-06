<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class BulkDeclarationsRequest extends Request {
    public function rules()
	{
		return [
            'data' => 'required|array',
			'data.*.amount' => 'required|numeric|gt:0',
			'data.*.date' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
			'data.*.gift' => 'required|boolean',
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
            'data.*.gift' => 'Gift',
            'data.*.file' => 'Bestand'
        ];
    }

    public function messages()
    {
        return [
            'data.required' => 'Er moet wel iets ingevuld worden'
        ];
    }
}