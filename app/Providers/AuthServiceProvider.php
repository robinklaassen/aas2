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
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
