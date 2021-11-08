<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\Comment' => 'App\Policies\CommentPolicy',
        'App\Models\Event' => 'App\Policies\EventPolicy',
        'App\Models\Member' => 'App\Policies\MemberPolicy',
        'App\Models\Location' => 'App\Policies\LocationPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy',
        'App\Models\Course' => 'App\Policies\CoursePolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Declaration' => 'App\Policies\DeclarationPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
