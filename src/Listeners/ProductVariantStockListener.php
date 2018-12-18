<?php

namespace Chang\Erp\Listeners;

use Chang\Erp\Events\InventoryIncrementEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductVariantStockListener
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
     * @param  InventoryIncrementEvent $event
     * @return void
     */
    public function handle(InventoryIncrementEvent $event)
    {
        info('变体id: [' .$event->model->product_variant_id . '] 库存增长 ' . $event->num);
        $event->model->variant()->increment('stock', $event->num);
    }
}
