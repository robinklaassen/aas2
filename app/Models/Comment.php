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
            case Member::class:
            case Participant::class:
                return $entity->volnaam;
            case Location::class:
                return $entity->naam;
            case Event::class:
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
        return $this->belongsTo(User::class);
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
