<?php

declare(strict_types=1);

namespace App\Events;

use App\Member;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

use Illuminate\Queue\SerializesModels;

class MemberUpdated
{
    use Dispatchable;

    use InteractsWithSockets;

    use SerializesModels;

    public $member;

    /**
     * Create a new event instance.
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }
}
