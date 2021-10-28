<?php

declare(strict_types=1);

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
        'App\Course' => 'App\Policies\CoursePolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Declaration' => 'App\Policies\DeclarationPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
