<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function listeners()
    {
        $json = file_get_contents('https://source.treehouseradios.com:8000/status-json.xsl');
        $resp = json_decode($json);
        return response()->json(['listeners' => $resp->icestats->source->listeners]);
    }
}
