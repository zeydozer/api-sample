<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use Bus, DB;

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

    public function batch(Request $r)
    {
        try {
            $this->result = Bus::findBatch($r->id);
        } catch (QueryException $e) {
            $this->result['message'] = $e->getMessage();
            $this->statusCode = 500;
        }
        return response()->json($this->result, $this->statusCode);
    }

    public function batchProgress()
    {
        try {
            $batches = DB::table('job_batches')
                ->where('pending_jobs', '>', 0)
                ->get();
            $this->result = $batches;
        } catch (QueryException $e) {
            $this->result['message'] = $e->getMessage();
            $this->statusCode = 500;
        }
        return response()->json($this->result, $this->statusCode);
    }
}