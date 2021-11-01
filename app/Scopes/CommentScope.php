<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CommentScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        if (\Auth::check() && ! \Auth::user()->can('showSecret', \App\Models\Comment::class)) {
            $builder->where('is_secret', '=', false);
        }

        // A just to be sure scope, never allow non admins to see any comments
        if (\Auth::check() && ! \Auth::user()->is_admin) {
            $builder->whereRaw('1=0');
        }

        $builder->orderBy('updated_at', 'desc');
    }
}
