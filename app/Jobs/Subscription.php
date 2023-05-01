<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Subscription as Subs;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Http;

class Subscription implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable;

    public $data;
    public $auth;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subs $data, $auth)
    {
        $this->data = $data;
        $this->auth = $auth;
        $this->onQueue('worker');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $user = User::where('u_id', $this->data->u_id)
                ->where('app_id', $this->data->app_id)
                ->select('os', 'token')
                ->first();
            $check = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->auth)
            ])->post('http://api-sample.local/api/' . $user->os, [
                'token' => $user->token,
                'app_id' => $this->data->app_id,
                'receipt' => random_int(1000, 9999)
            ]);
            if ($check->successful()) {
                $resp = $check->json();
                if ($resp['status']) {
                    if ($resp['expire'] < now()) {
                        $is_started = 0;
                        $is_renewed = 0;
                        $is_finished = 1;
                    } else {
                        if ($this->data->created_at < now())
                            $is_renewed = 1;
                        $is_started = 1;
                        $is_finished = 0;
                    }
                    Subs::where('id', $this->data->id)->update([
                        'finished_at' => $resp['expire'],
                        'is_finished' => $is_finished,
                        'is_started' => $is_started,
                        'is_renewed' => $is_renewed,
                    ]);
                } else
                    $this->release();
            } else
                $this->release();
        } catch (QueryException $e) {
            $this->release();
        }
    }
}