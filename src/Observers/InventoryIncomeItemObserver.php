<?php

namespace Chang\Erp\Observers;

use Chang\Erp\Models\InventoryIncomeItem;

class InventoryIncomeItemObserver
{

    public function saved(InventoryIncomeItem $item)
    {
        // 更新数量价格统计
        $item->inventoryIncome->autoCalcItems()->save();
    }

    public function creating(InventoryIncomeItem $item)
    {
        $item->product_id = $item->variant->product_id;
    }
}