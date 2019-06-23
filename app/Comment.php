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
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getEntityDescription()
    {
        switch ($this->entity_type) {
            case 'App\Member':
            case 'App\Participant':
                return $this->entity->volnaam;
            default:
                return "Onbekende entity";
        }
    }
}
