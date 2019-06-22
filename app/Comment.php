<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CommentScope;

class Comment extends Model
{
    public $timestamps = true;
    protected $table = 'comments';

    protected $fillable = ["text"];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CommentScope);
    }

    public function entity()
    {
        $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
