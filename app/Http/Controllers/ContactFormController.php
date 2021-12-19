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
        $recaptchaResult = $this->recaptchaValidator->validate($request->input('recaptcha'));
        if (! $recaptchaResult->isValid()) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Recaptcha is ongeldig. Probeer het opnieuw. Foutmelding: ' . $recaptchaResult->message(),
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
                'Bericht succesvol verstuurd.',
            ],
        ];
    }
}
