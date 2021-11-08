<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Carbon dates
    protected $dates = ['date'];

    // An action belongs to one member

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
