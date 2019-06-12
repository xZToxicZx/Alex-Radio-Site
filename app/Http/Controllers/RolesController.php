<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Role;
use App\role_perm;
use App\User;


class RolesController extends Controller
{
    function all()
    {
        if (!AuthController::hasPerm('view.panel.roles'))
            return response()->json(['status' => 'err', 'roles' => []], 401);

        $user = Auth::user();
        $roles = Role::where('priority', '<=', $user->role->priority)->orderBy('priority', 'desc')->get();

        if ($user->id === 1)
            $roles = Role::orderBy('priority', 'desc')->get();

        foreach ($roles as $role) {
            foreach ($role->perms as $key => $perm) {
                unset($role->perms[$key]);
                $role->perms[$perm->name] = true;
            }
        }

        return response()->json(['status' => 'success', 'roles' => $roles->toArray()]);
    }

    function create(Request $req)
    {
        $user = Auth::user();
        if (!AuthController::hasPerm('create.role'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);


        $perms = $req->perms;
        if ($req->perms)
            unset($req->perms);

        $role = Role::create($req->all());

        foreach ($perms as $perm => $allowed) {
            if ($allowed)
                role_perm::create(['name' => $perm, 'role_id' => $role->id]);
        }

        if ($req->default) {
            Role::where('default', 1)->update(['default', 0]);
        }

        $roles = Role::where('priority', '<=', $user->role->priority)->orderBy('priority', 'desc')->get();

        if ($user->id === 1)
            $roles = Role::orderBy('priority', 'desc')->get();

        foreach ($roles as $role) {
            foreach ($role->perms as $key => $perm) {
                unset($role->perms[$key]);
                $role->perms[$perm->name] = true;
            }
        }
        return response()->json(['status' => 'success', 'roles' => $roles->toArray()]);
    }

    function delete(Request $req)
    {
        $user = Auth::user();
        if (!AuthController::hasPerm('delete.role'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        $role = Role::find($req->id);

        foreach ($role->perms as $perm) {
            $perm->delete();
        }

        $role->destroy($req->id);

        $roles = Role::where('priority', '<=', $user->role->priority)->orderBy('priority', 'desc')->get();

        if ($user->id === 1)
            $roles = Role::orderBy('priority', 'desc')->get();

        foreach ($roles as $role) {
            foreach ($role->perms as $key => $perm) {
                unset($role->perms[$key]);
                $role->perms[$perm->name] = true;
            }
        }
        return response()->json(['status' => 'success', 'roles' => $roles->toArray()]);
    }

    function update(Request $req)
    {
        $user = Auth::user();
        $role = Role::find($req->id);
        if (!AuthController::hasPerm('update.role'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        $perms = $req->perms;
        if ($req->perms)
            unset($req->perms);

        foreach ($role->perms as $perm) {
            $perm->delete();
        }

        foreach ($perms as $perm => $allowed) {
            if ($allowed)
                role_perm::create(['name' => $perm, 'role_id' => $role->id]);
        }

        if ($req->default && !$role->default) {
            Role::where('default', 1)->update(['default' => 0]);
        }

        $role->update($req->all());

        $roles = Role::where('priority', '<=', $user->role->priority)->orderBy('priority', 'desc')->get();

        if ($user->id === 1)
            $roles = Role::orderBy('priority', 'desc')->get();

        foreach ($roles as $role) {
            foreach ($role->perms as $key => $perm) {
                unset($role->perms[$key]);
                $role->perms[$perm->name] = true;
            }
        }
        return response()->json(['status' => 'success', 'roles' => $roles->toArray()]);
    }

    function getTeam()
    {
        $roles = Role::where('default', 0)->get();
        $retvar = [];

        foreach ($roles as $role) {
            $retvar[$role->name] = [];
            foreach ($role->users as $user) {
                array_push($retvar[$role->name], ["name" => $user->name, "img" => $user->avatarURL, "socials" => $user->socials]);
            }
        }
        return response()->json($retvar);
    }
}
