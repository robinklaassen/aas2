<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // List all skills from the database keyed by id, so it can be used as HTML select form options
    public static function options()
    {
        return self::all()->pluck('tag', 'id');
    }

    public static function findOrCreateFromString(string $skill_id)
    {
        if (is_numeric($skill_id)) {
            $skill = self::find((int) $skill_id);
        } else {
            $skill = self::findOrCreateByTag($skill_id);
        }
        return $skill;
    }

    public static function findOrCreateByTag(string $tag)
    {
        $skill = self::firstWhere('tag', $tag);
        if ($skill === null) {
            $skill = self::create([
                'tag' => $tag,
            ]);
        }
        return $skill;
    }

    // A skill belongs to many members
    public function members()
    {
        return $this->belongsToMany('App\Models\Member')->withTimestamps();
    }
}
