<?php

namespace App\Mail\members;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserMember extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $username;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Member $member, $username, $password)
    {
        $this->member = $member;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.members.newUserMember')
            ->from([Config::get("mail.addresses.kamp")])
            ->to($this->member->email, $this->member->volnaam)
            ->subject('ANDERWIJS - Gebruikersaccount aangemaakt');
    }
}
