<?php

namespace App\Listeners;

use App\Events\Canceled;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Http;

class CanceledListener implements ShouldQueue
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
     * @param  \App\Events\Canceled  $event
     * @return void
     */
    public function handle(Canceled $event)
    {
        $result = Controller::httpCallback($event);
        if ($result !== true)
            $this->release();
    }
}
