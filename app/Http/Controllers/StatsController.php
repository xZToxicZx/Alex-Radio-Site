<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class StatsController extends Controller
{
    public function listeners()
    {
        $client = new Client();
        $json = $client->get('http://5.226.143.148:8000/status-json.xsl');
        $resp = json_decode($json);
        return response()->json(['listeners' => $resp->icestats->source->listeners]);
    }
}
