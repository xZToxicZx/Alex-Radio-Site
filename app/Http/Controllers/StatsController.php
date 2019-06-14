<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class StatsController extends Controller
{
    public function listeners()
    {
        $client = new Client();
        $json = $client->get('https://source.treehouseradios.com:8000/status-json.xsl');
        $resp = json_decode($json->getBody());
        return response()->json(['listeners' => $resp->icestats->source->listeners]);
    }
}
