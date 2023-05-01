<?php

namespace App\Http\Controllers;

use Bus, DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BatchController extends Controller
{
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
