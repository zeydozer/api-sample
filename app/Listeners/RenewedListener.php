<?php

namespace App\Listeners;

use App\Events\Renewed;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Http;

class RenewedListener implements ShouldQueue
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
     * @param  \App\Events\Renewed  $event
     * @return void
     */
    public function handle(Renewed $event)
    {
        $result = Controller::httpCallback($event);
        if ($result !== true)
            $this->release();
    }
}