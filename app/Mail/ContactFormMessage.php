<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Config;

final class ContactFormMessage extends Mailable
{
    use Queueable;

    public string $text;

    public string $name;

    public string $email;

    public string $phone;

    public function __construct(string $name, string $email, string $phone, string $text)
    {
        $this->text = $text;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function build()
    {
        $subject = sprintf('Nieuw bericht van: %s', $this->name);

        return $this->view('emails.contactFormMessage')
            ->from([Config::get('mail.addresses.aas')])
            ->to([Config::get('mail.addresses.kantoor')])
            ->subject($subject);
    }
}
