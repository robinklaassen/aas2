<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;



class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        'App\Comment' => 'App\Policies\CommentPolicy',
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
