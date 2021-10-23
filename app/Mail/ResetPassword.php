<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        $isMember = $this->user->profile_type == 'App\Member';
        $to = [
            "email" => $isMember ? $this->user->profile->email : $this->user->profile->email_ouder,
            "name" => $isMember ? $this->user->profile->volnaam : $this->user->profile->parentName,
        ];

        $subject = sprintf('%s Wachtwoord gereset', Config::get('mail.subject_prefix.external'));

        return $this->view('emails.resetPassword')
            ->from([Config::get("mail.addresses.aas")])
            ->to([$to])
            ->subject($subject);
    }
}
