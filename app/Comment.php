<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $timestamps = true;

    protected $fillable = ["text"];

    public function entity()
    {
        $this->morphTo();
    }
}
