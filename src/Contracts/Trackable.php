<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 3:55 PM
 */

namespace Chang\Erp\Contracts;


interface Trackable
{
    // 对应物流信息
    public function tracks();

    // 是否发货
    public function isShipped(): bool;

}