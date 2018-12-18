<?php

namespace Chang\Erp\Events;

use Chang\Erp\Models\Inventory;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InventoryIncrementEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;

    public $num;

    /**
     * Create a new event instance.
     *
     * @param Inventory $model
     * @param $num
     */
    public function __construct(Inventory $model, $num)
    {
        $this->model = $model;
        $this->num = $num;
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
