<?php

declare(strict_types=1);

namespace App\Mail\members;

use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class NewUserMember extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $member;

    public $username;

    public $password;

    public function __construct(Member $member, $username, $password)
    {
        $this->member = $member;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('emails.members.newUserMember')
            ->from([Config::get('mail.addresses.kamp')])
            ->to($this->member->email, $this->member->volnaam)
            ->subject('ANDERWIJS - Gebruikersaccount aangemaakt');
    }
}
