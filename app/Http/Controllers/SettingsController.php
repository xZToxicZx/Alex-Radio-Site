<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Rule;
use App\Settings;
use App\BSong;

class SettingsController extends Controller
{
    function addRule(Request $req) {
        if (!AuthController::hasPerm('add.rule'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);
        if (!$req->name)
            return response()->json(['status' => 'err', 'err' => 'New rule required'], 422);

        Rule::create(['name' => $req->name]);

        return response()->json(['status' => 'success', 'rules' => Rule::all()]);
    }

    function delRule(Request $req) {
        if (!AuthController::hasPerm('delete.rule'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        Rule::destroy($req->id);

        return response()->json(['status' => 'success', 'rules' => Rule::all()]);
    }

    function addBSong(Request $req) {
        if (!AuthController::hasPerm('add.bsong'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        BSong::create(["name" => $req->name]);

        return response()->json(['status' => 'success', 'bsongs' => BSong::all()]);
    }

    function delBSong(Request $req) {
        if (!AuthController::hasPerm('delete.bsong'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        BSong::destroy($req->id);

        return response()->json(['status' => 'success', 'bsongs' => BSong::all()]);
    }

    function returnSettings() {
        $data = [];

        $settings = Settings::all();
        $data["rules"] = Rule::all();
        $data["bsongs"] = BSong::all();

        foreach ($settings as $setting) {
            $data[$setting['name']] = $setting['value'];
        }
        return $data;
    }

    function getSettings()
    {
        return response()->json($this->returnSettings());
    }

    function updateSettings(Request $req)
    {
        if (!AuthController::hasPerm('update.sitesettings'))
            return response()->json(['status' => 'err', 'err' => 'Insufficent permissions']);

        $name = $req->input('siteName');
        $paypal = $req->input('paypal');
        $rDelay = $req->input('rDelay');
        $rADjDelay = $req->input('rADjDelay');
        $info = $req->input('info');

        $update = [];
        if ($info)
        {
            Settings::where('name', 'info')->update(['value' => $info]);
            return response()->json(['status' => 'success', 'settings' => $this->returnSettings()]);
        }
        if ($name)
            Settings::where('name', 'siteName')->update(['value' => $name]);
        if ($paypal)
            Settings::where('name', 'paypal')->update(['value' => $paypal]);
        if ($rDelay)
            Settings::where('name', 'requestDelay')->update(['value' => $rDelay]);
        if ($rADjDelay)
            Settings::where('name', 'requestAutoDJDelay')->update(['value' => $rADjDelay]);
        return response()->json(['status' => 'success', 'settings' => $this->returnSettings()]);
    }
}
