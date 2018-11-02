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
        $income->price = $income->calcTotalPrice();
        $income->pcs = $income->calcTotalPcs();
    }
}