<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $guarded = ['user_id', 'role_id', 'created_at', 'created_by'];
    const UPDATED_AT = null;
    public $timestamps = true;


    public function creating($model) {
        $model->created_by = Auth::user()->id;
    }
}
