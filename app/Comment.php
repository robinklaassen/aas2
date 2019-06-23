<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CommentScope;

class Comment extends Model
{
    public $timestamps = true;
    protected $table = 'comments';

    protected $fillable = ["text", "is_secret"];

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

    public function getEntityDescriptionAttribute()
    {
        switch ($this->entity_type) {
            case 'App\Member':
            case 'App\Participant':
                return $this->entity->volnaam;
            case 'App\Location':
                return $this->entity->naam;
            case 'App\Event':
                return '(' . $this->entity->code . ')' . $this->entity->naam;

            default:
                return "Onbekende entity";
        }
    }
}
