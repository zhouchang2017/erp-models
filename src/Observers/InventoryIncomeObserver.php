<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\InventoryIncome;

class InventoryIncomeObserver
{
    public function saving(InventoryIncome $income)
    {
        $this->autoCalcItems($income);

        // 标记审核时间
        if ($this->status === InventoryIncome::UN_SHIP) {
            $this->updateConfirmedAt($income);
        }
    }


    /**
     * 计算items 价格|数量
     * @param InventoryIncome $income
     */
    protected function autoCalcItems(InventoryIncome $income)
    {
        $income->price = $income->calcTotalPrice();
        $income->pcs = $income->calcTotalPcs();
    }

    protected function updateConfirmedAt($income)
    {
        $income->confirmed_at = now();
    }
}