<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/1
 * Time: 下午11:40
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Order;

trait OrderableTrait
{
    public function order()
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function register()
    {
        return [
            'order_status' => $this->getStatus(),
            'market_id' => $this->market_id,
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}