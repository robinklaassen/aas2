<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Capability;
use App\Role;
use Illuminate\Http\Response;

final class RolesController extends Controller
{
    public function explain()
    {
        $capabilities = Capability::with('roles')->orderBy('description')->get();
        $roles = Role::query()->orderBy('title')->get();

        $capabilitiesPerRole = [];
        /** @var Role $role */
        foreach ($roles as $role) {
            $capabilitiesPerRole[$role->id] = $role->capabilities->pluck('id');
        }

        return view('roles.explain', compact('capabilities', 'capabilitiesPerRole', 'roles'));
    }
}
