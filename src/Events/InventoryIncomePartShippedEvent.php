<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/11
 * Time: 上午10:40
 */

namespace Chang\Erp\Events;

use Chang\Erp\Models\InventoryIncome;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InventoryIncomePartShippedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;

    public function __construct(InventoryIncome $model)
    {
        $this->model = $model;
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