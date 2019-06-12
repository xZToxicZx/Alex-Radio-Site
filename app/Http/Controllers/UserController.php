<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AuthController;
use App\User;

class UserController extends Controller
{
    public function all()
    {
        if (!AuthController::hasPerm('view.panel.users') || !AuthController::hasPerm('claim.dj.slot'))
            return response()->json(['status' => 'err', 'users' => []]);

        $users = User::all();

        foreach ($users as $user) {
            $user->role->perms;
            $user->makeHidden(['role_id']);
        }

        return response()->json(
            [
                'status' => 'success',
                'users' => $users->toArray()
            ], 200);
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return response()->json(
            [
                'status' => 'success',
                'user' => $user->toArray()
            ], 200);
    }

    public function update(Request $req)
    {
        if (!AuthController::hasPerm('edit.user') || !$req->id) {
            $req->validate([
                'name'  => 'min:3',
                'password'  => 'required|min:3',
                'new_password' => 'min:3|confirmed',
                'avatar' => 'dimensions:min_width=120,min_height=120,ratio=1|image',
                'email' => 'email|unique:users,email,'.Auth::user()->id
            ]);

            $user = User::find(Auth::user()->id);
            if (!Hash::check($req->password, Auth::user()->password))
                return response()->json(['status' => 'error', 'errors' => ['password' => ['Incorrect password']]], 422);
            $updates = $req->except(['avatar', 'password', 'new_password', 'new_password_confirmation']);
            if ($req->hasFile('avatar')) {
                if ($user->avatarURL != '/storage/avatar/default.png')
                    Storage::delete($user->avatarURL);
                $filename = Hash::make($req->avatar->getClientOriginalName()).'_'.$user->id.'_'.time().'.'.$req->avatar->extension();
                $filename = str_replace(' ', '_', $filename);
                $req->avatar->storeAs('', $filename, 'avatars');
                $updates['avatarURL'] = '/storage/avatars/'.$filename;
            };
            if (isset($req->new_password))
                $updates['password'] = bcrypt($req->new_password);
            $user->update($updates);
            return response()->json(['status' => 'success', 'user' => $user->refresh()->toArray()]);
        } else {
            $v = $req->validate([
                'name'  => 'min:3',
                'email' => 'email|unique:users,email,'.$req->id
            ]);

            $user = User::find($req->id);
            $user->update($req->all());

            $users = User::all();

            foreach ($users as $user) {
                $user->role;
                $user->makeHidden(['role_id']);
            }
            return response()->json(['status' => 'success', 'users' => $users]);
        }
    }
    public function delete(Request $req)
    {
        if (!AuthController::hasPerm('delete.user'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions.']);

        if ($req->id == Auth::user()->id)
            return response()->json(['status' => 'err', 'err' => 'You cant delete yourself!']);

        User::destroy($req->id);

        $users = User::all();

        foreach ($users as $user) {
            $user->role;
            $user->makeHidden(['role_id']);
        }
        return response()->json(['status' => 'success', 'users' => $users]);
    }
}
