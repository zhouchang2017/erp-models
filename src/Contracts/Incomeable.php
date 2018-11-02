<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 4:03 PM
 */

namespace Chang\Erp\Contracts;


interface Incomeable
{
    public function inventoryIncomes();

    public function inventoryIncome();
}