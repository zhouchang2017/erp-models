<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\InventoryExpendItem;

class InventoryExpendItemObserver
{

    public function saved(InventoryExpendItem $item)
    {
        // 更新数量价格统计
        $item->inventoryExpend->autoCalcItems()->save();
    }

    public function creating(InventoryExpendItem $item)
    {
        $item->product_id = $item->variant->product->id;
    }
}