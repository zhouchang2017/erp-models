<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\InventoryExpend;

class InventoryExpendObserver
{
    public function saving(InventoryExpend $expend)
    {
        $this->autoCalcItems($expend);

        // 标记审核时间
        if ((int)$expend->status === InventoryExpend::UN_SHIP) {
            $this->updateConfirmedAt($expend);
        }

    }

    /**
     * 计算items 价格|数量
     * @param InventoryExpend $expend
     */
    protected function autoCalcItems(InventoryExpend $expend)
    {
        $expend->price = $expend->calcTotalPrice();
        $expend->pcs = $expend->calcTotalPcs();
    }

    protected function updateConfirmedAt($expend)
    {
        $expend->confirmed_at = now();
    }


}