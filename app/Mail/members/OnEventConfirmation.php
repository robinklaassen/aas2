<?php

namespace App\Mail\members;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnEventConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.members.onEventConfirmation')
            ->subject("AAS 2.0 - Aangemeld voor kamp")
            ->from([Config::get("mail.addresses.aas")])
            ->to($this->member->email_anderwijs, $this->member->volnaam);
    }
}
