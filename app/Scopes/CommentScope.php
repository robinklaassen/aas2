<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CommentScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (\Auth::check() && \Auth::user()->is_admin != 2) {
            $builder->where('is_secret', '=', false);
        }

        // A just to be sure scope, never allow non admins to see any comments
        if (\Auth::check() && !\Auth::user()->is_admin) {
            $builder->whereRaw('1=0');
        }


        $builder->orderBy('updated_at', 'desc');
    }
}
