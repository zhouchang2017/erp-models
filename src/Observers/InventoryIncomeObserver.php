<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\InventoryIncome;
use Chang\Erp\Services\InventoryIncomeService;

class InventoryIncomeObserver
{

    public function created(InventoryIncome $income)
    {
        (new InventoryIncomeService($income))->statusToSaved();
    }

}