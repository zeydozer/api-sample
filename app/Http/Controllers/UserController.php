<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Session, Hash;

class UserController extends Controller
{
    public $result = [
        'error' => false
    ];
    public $statusCode = 200;

    public function store(Request $r)
    {
        if ($r->session()->missing('u_id'))
            $r->session()->put('u_id', $r->u_id);
        try {
            if ($r->session()->has('token'))
                $this->result['token'] = $r->session()->get('token');
            else {
                $user = User::find($r->u_id);
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
                $r->session()->put('token', $user->token);
            }
        } catch (Exception $e) {
            $this->result['error'] = true;
            $this->result['message'] = $e->getMessage();
            $this->statusCode = 500;
        }
        return response()->json($this->result, $this->statusCode);
    }
}