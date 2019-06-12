<?php

namespace App\Http\Controllers;

use App\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Validator;

class RequestsController extends Controller
{
    function all()
    {
        return response()->json(Request::select('id', 'name', 'message')->get());

    }
    function delete(Req $req)
    {
        if (AuthController::hasPerm("delete.request"))
            if (!$req->id)
                Request::truncate();
            else
                Request::destroy($req->id);
        $requests = Request::select('id', 'name', 'message')->get();
        return response()->json(['status' => 'success', 'requests' => $requests], 200);
    }
    function create(Req $req)
    {
        $v = Validator::make($req->all(), [
            'name' => 'required',
            'message'  => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        // Check if the user has made a request in x amount of time.
        $request = $req->all();
        $request['ip'] = $req->ip();
        Request::create($request);
        return response()->json(['status' => 'success'], 200);
    }
}
