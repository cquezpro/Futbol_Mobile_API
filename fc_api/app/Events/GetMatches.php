<?php

namespace App\Events;

use App\Matches;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetMatches implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $matches;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($matches)
    {
        $this->matches = $matches;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.GetMatches');
    }

    public function broadcastAs()
    {
        return 'reload.matches';
    }

    public function broadcastWith()
    {
        //$encode = base64_encode(json_encode($this->matches));
        //\Log::warning($encode);
        return [
            'data' => $this->matches,
        ];
    }
}
