<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\InventoryIncomeItem;

class InventoryIncomeItemObserver
{
    public function creating(InventoryIncomeItem $item)
    {
        $item->product_id = $item->variant->product->id;
    }
}