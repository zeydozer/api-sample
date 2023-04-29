<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SubController extends Controller
{
    public function verification(Request $r)
    {
        try {
            $last = substr($r->receipt, -1);
            if (intval($last) % 2 == 1) {
                $this->result['status'] = true;
                $expire = new \DateTime('now', new \DateTimeZone('-0600'));
                $this->result['expire'] = $expire->format('Y-m-d H:i:s');
            } else {
                $this->result['status'] = false;
                $this->result['message'] = 'Geçersiz receipt.';
            }
        } catch (Exception $e) {
            $this->result['status'] = false;
            $this->result['message'] = $e->getMessage();
            $this->statusCode = 500;
        }
        return response()->json($this->result, $this->statusCode);
    }

    public function check(Request $r)
    {
        try {
            $subs = Subscription::where('u_id', $r->user->u_id)
                ->where('app_id', $r->user->app_id)
                ->first();
            $this->result = new SubscriptionResource($subs);
        } catch (QueryException $e) {
            $this->result['message'] = $e->getMessage();
            $this->statusCode = 500;
        }
        return response()->json($this->result, $this->statusCode);
    }
}