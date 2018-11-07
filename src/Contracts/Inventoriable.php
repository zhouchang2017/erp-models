<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/7
 * Time: 下午10:02
 */

namespace Chang\Erp\Contracts;


interface Inventoriable
{
    // 消费者 去处 订单|仓库|退货
    public function customer();

    // 生产者 来源 供应商|仓库
    public function producer();

    // 入库id
    public function toWarehouseId(): int;

    // 出库id
    public function formWarehouseId(): int;

    // 减库存
    public function decrementInventory();

    // 加库存
    public function incrementInventory();

    public function items();

}