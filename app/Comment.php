<?php

namespace App;

use App\Scopes\CommentScope;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public static function getEntityDescription($entityType, $entity)
    {
        switch ($entityType) {
            case 'App\Member':
            case 'App\Participant':
                return $entity->volnaam;
            case 'App\Location':
                return $entity->naam;
            case 'App\Event':
                return '(' . $entity->code . ')' . $entity->naam;
            default:
                return "Onbekende entity";
        }
    }

    public static function getEntityDescriptionByKey($entityType, $key)
    {
        $entity = $entityType::findOrFail($key);
        return Comment::getEntityDescription($entityType, $entity);
    }

    public $timestamps = true;
    protected $table = 'comments';

    protected $fillable = ["text", "is_secret"];

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new CommentScope);
    }

    public function entity()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopePublic($query)
    {
        return $query->where('is_secret', false);
    }

    public function getEntityDescriptionAttribute()
    {
        return Comment::getEntityDescription($this->entity_type, $this->entity);
    }
}
