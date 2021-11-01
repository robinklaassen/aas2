<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeModelNamespaces extends Migration
{
    public const CHANGED_MODELS = [
        'App\Member' => 'App\Models\Member',
        'App\Participant' => 'App\Models\Participant',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        foreach (self::CHANGED_MODELS as $old => $new) {
            DB::table('users')->where('profile_type', $old)->update([
                'profile_type' => $new,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        foreach (self::CHANGED_MODELS as $old => $new) {
            DB::table('users')->where('profile_type', $new)->update([
                'profile_type' => $old,
            ]);
        }
    }
}
