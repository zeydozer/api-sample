<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

use DB;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subs:report {--start-date=} {--end-date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscriptions situation count report by app and device and day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $datas = DB::table('subscriptions AS s')
                ->select('s.app_id', 's.updated_at_date', 'u.os')
                ->selectRaw('SUM(s.is_renewed) AS renew_quantity')
                ->selectRaw('SUM(s.is_finished) AS finish_quantity')
                ->join('users AS u', function ($q) {
                    $q->on('u.u_id', '=', 's.u_id')
                        ->on('u.app_id', '=', 's.app_id');
                })
                ->whereBetween('s.updated_at_date', [
                    $this->option('start-date'),
                    $this->option('end-date')
                ])
                ->groupBy('s.app_id', 's.updated_at_date', 'u.os')
                ->orderByRaw('s.updated_at_date, s.app_id, u.os')
                ->get();
            $this->info(json_encode($datas));
        } catch (QueryException $e) {
            $this->info($e->getMessage());
        }
    }
}