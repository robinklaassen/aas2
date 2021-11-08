<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ['date'];

    public static function empty(): self
    {
        return new self([
            'description' => '-',
            'points' => 0,
        ]);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
