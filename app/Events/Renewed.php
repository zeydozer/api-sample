<?php

namespace App\Events;

use App\Models\Subscription;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Renewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subs;
    public $token;
    public $name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscription $subs)
    {
        $this->subs = $subs;
        $this->token = '$2y$10$cDEFsALIWRQ/bwsrTQhYFuZTzKbg4tp2B0ESqgbEpMRI50bOEGHw.';
        $this->name = 'renewed';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
