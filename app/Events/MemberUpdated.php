<?php

namespace App\Events;

use App\Member;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

use Illuminate\Queue\SerializesModels;

class MemberUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }
}
