<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = true;
    protected $table = 'comments';

    protected $fillable = ["text"];

    public function entity()
    {
        $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
