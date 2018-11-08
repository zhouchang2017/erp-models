<?php

namespace Chang\Erp\Listeners;

use Chang\Erp\Events\ShippedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeStatusToShippedListener
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
        $event->model->statusToShipped();
        info('to-shipped-handle');
    }
}
