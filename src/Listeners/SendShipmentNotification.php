<?php

namespace Chang\Erp\Listeners;

use Chang\Erp\Events\Shipped;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendShipmentNotification
{
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
     * @param  Shipped  $event
     * @return void
     */
    public function handle(Shipped $event)
    {
        //TODO 发货消息通知
        info('send-notification-handle');
    }
}
