<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/21
 * Time: 12:32 PM
 */

namespace Chang\Erp\Observers;


use Chang\Erp\Models\Order;

class OrderObserver
{
    // 新订单
    public function created(Order $order)
    {
        // 锁定库存
    }

    // 订单更新
    public function updated(Order $order)
    {
        info('订单更新' . $order->id);
    }
}