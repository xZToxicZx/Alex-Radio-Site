<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Role;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $v = Validator::make($req->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = new User;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->role_id = Role::where('default', 1)->first()->id;
        $user->save();
        return response()->json(['status' => 'success'], 200);
    }
    public function login(Request $req)
    {
        $v = Validator::make($req->all(), [
            'email' => 'required|email',
            'password'  => 'required|min:3',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $creds = $req->only('email', 'password');
        if ($token = $this->guard()->attempt($creds)) {
            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
        return response()->json(['error' => 'login_error'], 401);
    }
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function user(Request $req)
    {
        $user = User::find(Auth::user()->id);
        $user->role->perms;
        $user->makeHidden(['role_id']);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    public function checkPerms(Request $req)
    {
        if ($this->hasPerm($req->perm))
            return response()->json(["hasperm" => true]);
        else
            return response()->json(["hasperm" => false]);
    }

    private function guard()
    {
        return Auth::guard();
    }

    public static function hasPerm($perm)
    {
        if (!Auth::user())
            return false;
        $user = User::find(Auth::user()->id);
        $roleperms = [];
        if ($user)
            $roleperms = Role::find($user->role_id)->perms;

        if ($perm) {
            foreach ($roleperms as $roleperm) {
                if ($roleperm->name === $perm || $roleperm->name === "all") {
                    return true;
                }
            }
        }

        return false;
    }
}
