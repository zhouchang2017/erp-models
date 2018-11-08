<?php

namespace Chang\Erp\Listeners;

use Chang\Erp\Events\ShippedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendShipmentNotificationListener
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
     * @param  ShippedEvent  $event
     * @return void
     */
    public function handle(ShippedEvent $event)
    {
        //TODO 发货消息通知
        info('send-notification-handle');
    }
}
