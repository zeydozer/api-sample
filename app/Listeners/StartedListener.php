<?php

namespace App\Listeners;

use App\Events\Started;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Http;

class StartedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'callback';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Started  $event
     * @return void
     */
    public function handle(Started $event)
    {
        $result = Controller::httpCallback($event);
        if ($result !== true)
            $this->release();
    }
}