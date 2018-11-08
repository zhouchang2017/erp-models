<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\Inventory;
use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\Warehouse;

class InventoryExpendObserver
{
    public function saving(InventoryExpend $expend)
    {
        // 标记审核时间
        if ((int)$expend->status === InventoryExpend::UN_SHIP) {
            $expend->updateConfirmedAt();
        }
    }
}