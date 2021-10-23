<?php

namespace App\Helpers;

use App\Capability;
use App\Role;

class CapabilityHelper
{
    public static function detach($capa, $role)
    {
        $capa = Capability::where("name", "=", $capa)->firstOrFail();
        $role = Role::where("tag", "=", $role)->firstOrFail();
        $role->capabilities()->detach($capa->id);
    }

    public static function attach($capa, $role)
    {
        $capa = Capability::where("name", "=", $capa)->firstOrFail();
        $role = Role::where("tag", "=", $role)->firstOrFail();
        $role->capabilities()->attach($capa->id);
    }

    public static function new($capa, $description, $role)
    {
        $capa = Capability::create([
            "name" => $capa,
            "description" => $description
        ]);
        $role = Role::where("tag", "=", $role)->firstOrFail();
        $role->capabilities()->attach($capa->id);
    }

    public static function delete($capa)
    {
        if (!is_array($capa)) {
            $capa = [$capa];
        }

        Capability::whereIn('id', $capa)->delete();
    }
}
