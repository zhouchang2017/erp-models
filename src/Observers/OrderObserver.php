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
        $order->getExpendItemList()->each->lock();
    }

    // 订单更新
    public function updated(Order $order)
    {

        if($order->order_status === Order::CANCEL){
            info('订单关闭' . $order->id);
            $order->getExpendItemList()->each->revert();
        }
    }
}