<?php

namespace App\Mail\participants;

use App\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class NewUserParticipant extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $username;
    public $password;

    public function __construct(Participant $participant, $username, $password)
    {
        $this->participant = $participant;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('emails.participants.newUserParticipant')
            ->from([Config::get("mail.addresses.kantoor")])
            ->to([$this->participant->getParentEmail()])
            ->subject('ANDERWIJS - Gebruikersaccount aangemaakt');
    }
}
