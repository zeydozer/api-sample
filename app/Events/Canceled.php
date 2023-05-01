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

class Canceled
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
        $this->token = '$2y$10$kAfhGF2zUeauXpUR2a9sZef3DVNLJnynxXbW9MKymljGMZ6xktDKi';
        $this->name = 'canceled';
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
