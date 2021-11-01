<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Roles which are hidden in the index view for non administrative members
    public const HIDDEN_ROLES = [
        // member is implicit; every member has this role
        'member',
        // participant is implicit; every participant has this role
        'participant',
    ];

    public $timestamps = false;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_role');
    }

    public function capabilities()
    {
        return $this->belongsToMany('App\Models\Capability', 'role_capability');
    }
}
