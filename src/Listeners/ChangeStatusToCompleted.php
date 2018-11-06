<?php

namespace Chang\Erp\Listeners;

use Chang\Erp\Events\InventoryPut;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeStatusToCompleted
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
     * @param  InventoryPut  $event
     * @return void
     */
    public function handle(InventoryPut $event)
    {
        $event->model->statusToCompleted();
        info('to-completed-handle');
    }
}
