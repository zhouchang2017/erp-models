<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 6:02 PM
 */

namespace Chang\Erp\Traits;


trait InventoryCalculationTrait
{
    public function calcTotalPcs()
    {
        return $this->items()->sum('pcs');
    }

    public function calcTotalPrice()
    {
        return $this->items()->sum('price');
    }
}