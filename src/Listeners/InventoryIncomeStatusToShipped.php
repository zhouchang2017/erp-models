<?php

namespace Chang\Erp\Listeners;

use Chang\Erp\Events\InventoryIncomeShipped;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InventoryIncomeStatusToShipped
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
     * @param  InventoryIncomeShipped  $event
     * @return void
     */
    public function handle(InventoryIncomeShipped $event)
    {
        info('to-shipped-handle');
    }
}
