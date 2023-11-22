<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatingCompaniesLatLonEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastAs()
    {
        return 'Companies';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('Companies');
    }

    public function broadcastWith()
    {
        return ['data'=> $this->data];
    }
}
