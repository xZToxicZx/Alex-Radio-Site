<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class SongController extends Controller
{
    public function current()
    {
        $client = new Client();
        $json = $client->get('http://5.226.143.148:8001/stats?sid=1&json=1')->getBody();
        $json = json_decode($json, true);
        $song = explode(' - ', $json['songtitle']);
        $token;
        if (!Cache::has('spotifyToken'))
        {
            $res = $client->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ],
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode('f11a1c871e964b6ab4e0051e20b72031' . ':' . 'b6290e4b098749369e9a2c49d89a4fe8')
                ]
            ]);

            $token = json_decode($res->getBody());

            Cache::put('spotifyToken', $token, $token->expires_in);
        }
        else
        {
            $token = Cache::get('spotifyToken');
        }


        $res = $client->get('https://api.spotify.com/v1/search?type=track&q='.$song[1].' '.$song[0].'&limit=1', ['headers' => ['Authorization' => $token->token_type . ' ' . $token->access_token]]);

        $ssong = json_decode($res->getBody())->tracks->items[0] ?? null;

        //dd($ssong);
        echo json_encode([ 'artist' => $ssong->artists[0]->name ?? $song[0], 'name' => $ssong->name ?? $song[1], 'img' => $ssong->album->images[2]->url ?? "/storage/logo.png" ]);
    }

    public function recent()
    {
        $retsongs = [];
        $client = new Client();
        $json = $client->get('http://5.226.143.148:8001/admin.cgi?sid=1&pass=4k0$ZF2Gw*3f&mode=viewjson&page=4');
        $songs = json_decode($json->getBody());
        $token;

        if (!Cache::has('spotifyToken'))
        {
            $res = $client->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ],
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode('f11a1c871e964b6ab4e0051e20b72031' . ':' . 'b6290e4b098749369e9a2c49d89a4fe8')
                ]
            ]);

            $token = json_decode($res->getBody());

            Cache::put('spotifyToken', $token, $token->expires_in);
        }
        else
        {
            $token = Cache::get('spotifyToken');
        }


        foreach ($songs as $key => $song) {
            $song = explode(' - ', $song->title);
            if (!isset($song[1]))
            {
                $song[1] = $song[0];
                $song[0] = "";
            }
            $res = $client->get('https://api.spotify.com/v1/search?type=track&q='.$song[1].' '.$song[0], ['headers' => ['Authorization' => $token->token_type . ' ' . $token->access_token]]);

            $ssong = json_decode($res->getBody())->tracks;

            $ssong = $ssong->items[0] ?? null;

            $retsongs[$key] = ['artist' => $ssong->artists[0]->name ?? $song[0], 'name' => $ssong->name ?? $song[1], 'img' => $ssong->album->images[2]->url ?? "/storage/logo.png"];
            if ($key === 6)
                break;
        }

        echo json_encode($retsongs);
    }
}
