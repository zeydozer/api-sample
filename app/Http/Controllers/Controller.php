<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $statusCode = 200;
    public $result = [];

    public static function httpCallback($event)
    {
        try {
            $client = Http::withHeaders([
                'Authorization' => 'Bearer ' . $event->token
            ])->post('http://api-sample.local/api/callback', [
                'u_id' => $event->subs->u_id,
                'app_id' => $event->subs->app_id,
                'event' => $event->name
            ]);
            if (!in_array($client->status(), [200, 201]))
                return $client->status();
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
}