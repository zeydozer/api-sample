<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubController extends Controller
{
    public function check(Request $r)
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
}