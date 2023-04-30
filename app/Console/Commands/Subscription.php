<?php

namespace App\Console\Commands;

use App\Jobs\Subscription as SubsJob;
use App\Models\Subscription as Subs;
use Illuminate\Console\Command;
use Bus;

class Subscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subs:check {--username=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscription and database process';

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
        $datas = Subs::where('is_finished', 0)
            ->where('finished_at', '<', now())
            ->select('u_id', 'app_id', 'id')
            ->limit(10000)
            ->get();
        if (count($datas) > 0) {
            $auth = $this->option('username') . ':' . $this->option('password');
            $batch = Bus::batch([])->dispatch();
            foreach ($datas as $data)
                $batch->add(new SubsJob($data, $auth));
            $this->info(json_encode($batch));
        }
    }
}