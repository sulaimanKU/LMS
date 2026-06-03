<?php

namespace App\Events;
use App\Models\OnlineClass;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClassStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $onlineClass;

    /**
     * Create a new event instance.
     */
   public function __construct(OnlineClass $onlineClass)
    {
        $this->onlineClass = $onlineClass;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

   public function broadcastOn(): array
    {

        return [
            new Channel('online-classes'),
        ];

    }
    public function broadcastAs()
    {

        return 'class.started';
    }
}
