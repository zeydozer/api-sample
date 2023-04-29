<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\Subscription;

use Hash;

class UserController extends Controller
{
    public function store(Request $r)
    {
        if ($r->session()->missing('register'))
            $r->session()->put('register', $r->u_id . ':' . $r->app_id);
        try {
            $user = User::where('u_id', $r->u_id)->where('app_id', $r->app_id)->first();
            if (!$user) {
                $user = new User;
                $user->u_id = $r->u_id;
                $user->app_id = $r->app_id;
                $user->lang = $r->lang;
                $user->os = $r->os;
                $user->token = Hash::make($user->u_id . ':' . $user->app_id);
                $user->save();
            }
            $this->result['token'] = $user->token;
        } catch (QueryException $e) {
            $this->result['message'] = $e->getMessage();
            $this->statusCode = 500;
        }
        return response()->json($this->result, $this->statusCode);
    }

    public function purchase(Request $r)
    {
        try {
            $check = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($r->user->u_id . ':' . $r->user->app_id)
            ])->post('http://api-sample.local/api/' . $r->user->os, [
                // 'token' => $r->user->token,
                'receipt' => $r->receipt
            ]);
            if ($check->successful()) {
                $resp = $check->json();
                if ($resp['status']) {
                    $subs = Subscription::where('u_id', $r->user->u_id)
                        ->where('app_id', $r->user->app_id)
                        ->first();
                    if (!$subs) {
                        $subs = new Subscription;
                        $subs->u_id = $r->user->u_id;
                        $subs->app_id = $r->user->app_id;
                    }
                    $subs->finished_at = $resp['expire'];
                    $subs->save();
                    $this->result = new SubscriptionResource($subs);
                } else {
                    $this->result['message'] = $resp['message'];
                    $this->statusCode = 500;
                }
            } else {
                $this->result['message'] = 'Doğrulama başarısız.';
                $this->statusCode = $check->status();
            }
        } catch (QueryException $e) {
            $this->result['message'] = $e->getMessage();
            $this->statusCode = 500;
        }
        return response()->json($this->result, $this->statusCode);
    }
}