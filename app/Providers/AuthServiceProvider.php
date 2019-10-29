<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Comment;
use App\Policies\CommentPolicy;


class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Comment::class => CommentPolicy::class,
        'App\Event' => 'App\Policies\EventPolicy',
        'App\Member' => 'App\Policies\MemberPolicy',
        'App\Location' => 'App\Policies\LocationPolicy',
        'App\Role' => 'App\Policies\RolePolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
