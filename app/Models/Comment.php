<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = true;

    protected $table = 'comments';

    protected $fillable = ['text', 'is_secret'];

    public static function getEntityDescription($entityType, $entity)
    {
        switch ($entityType) {
            case 'App\Models\Member':
            case 'App\Models\Participant':
                return $entity->volnaam;
            case 'App\Models\Location':
                return $entity->naam;
            case 'App\Models\Event':
                return '(' . $entity->code . ')' . $entity->naam;
            default:
                return 'Onbekende entity';
        }
    }

    public static function getEntityDescriptionByKey($entityType, $key)
    {
        $entity = $entityType::findOrFail($key);
        return self::getEntityDescription($entityType, $entity);
    }

    public function entity()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopePublic($query)
    {
        return $query->where('is_secret', false);
    }

    public function getEntityDescriptionAttribute()
    {
        return self::getEntityDescription($this->entity_type, $this->entity);
    }

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new CommentScope);
    }
}
