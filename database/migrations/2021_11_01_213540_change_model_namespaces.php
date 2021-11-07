<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeModelNamespaces extends Migration
{
    public const CHANGED_USER_MODELS = [
        'App\Member' => 'App\Models\Member',
        'App\Participant' => 'App\Models\Participant',
    ];

    public const CHANGED_COMMENT_MODELS = [
        'App\Event' => 'App\Models\Event',
        'App\Location' => 'App\Models\Location',
        'App\Member' => 'App\Models\Member',
        'App\Participant' => 'App\Models\Participant',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        foreach (self::CHANGED_USER_MODELS as $old => $new) {
            DB::table('users')->where('profile_type', $old)->update([
                'profile_type' => $new,
            ]);
        }

        foreach (self::CHANGED_COMMENT_MODELS as $old => $new) {
            DB::table('comments')->where('entity_type', $old)->update([
                'entity_type' => $new,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        foreach (self::CHANGED_USER_MODELS as $old => $new) {
            DB::table('users')->where('profile_type', $new)->update([
                'profile_type' => $old,
            ]);
        }

        foreach (self::CHANGED_COMMENT_MODELS as $old => $new) {
            DB::table('comments')->where('entity_type', $new)->update([
                'entity_type' => $old,
            ]);
        }
    }
}
