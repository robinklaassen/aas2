<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // List all skills from the database keyed by id, so it can be used in HTML select
    public static function formArray()  // TODO better method name?
    {
        return Skill::all()->pluck('tag', 'id');
    }

    public static function findOrCreateFromString(string $skill_id)
    {
        if (is_numeric($skill_id)) {
            $skill = Skill::find((int) $skill_id);
        } else {
            $skill = Skill::findOrCreateByTag($skill_id);
        }
        return $skill;
    }

    public static function findOrCreateByTag(string $tag)
    {
        $skill = Skill::firstWhere('tag', $tag);
        if ($skill === null) {
            $skill = Skill::create(['tag' => $tag]);
        }
        return $skill;
    }

    // A skill belongs to many members
    public function members()
    {
        return $this->belongsToMany('App\Member')->withTimestamps();
    }
}
