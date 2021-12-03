<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Api\ContactFormRequest;
use App\Mail\ContactFormMessage;
use App\Services\Recaptcha\RecaptchaValidator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ContactFormController extends Controller
{
    private RecaptchaValidator $recaptchaValidator;

    public function __construct(RecaptchaValidator $recaptchaValidator)
    {
        $this->recaptchaValidator = $recaptchaValidator;
    }

    public function send(ContactFormRequest $request)
    {
        if (! $this->recaptchaValidator->validate($request->input('recaptcha'))) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Recaptcha is ongeldig. Probeer het opnieuw',
            ]);
        }

        Mail::send(
            new ContactFormMessage(
                $request->input('name'),
                $request->input('email'),
                $request->input('phone'),
                $request->input('message'),
            )
        );

        return [
            'messages' => [
                'Bericht successful verstuurd.',
            ],
        ];
    }
}
