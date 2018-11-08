<?php

namespace Chang\Erp\Models;

use Chang\Erp\Contracts\Orderable;
use Chang\Erp\Traits\ExpendableTrait;
use Chang\Erp\Traits\MoneyFormatableTrait;

class Order extends Model
{
    use MoneyFormatableTrait, ExpendableTrait;

    const PENDING = 0;              //等待买家付款
    const UN_SHIPPED = 1;            //买家已付款，等待卖家发货
    const PARTIALLY_SHIPPED = 2;    //部分发货
    const SHIPPED = 3;              //已发货
    const CANCEL = 4;               //订单已取消
    const UNFULFILLABLE = 5;       // 订单无法进行配送

    public $timestamps = false;

    protected $fillable = [
        'order_status',
        'market_id',
        'price',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function orderable()
    {
        return $this->morphTo();
    }

    public static function syncOrder(Orderable $orderable)
    {
        static::updateOrCreate([
            'orderable_type' => get_class($orderable),
            'orderable_id' => $orderable->id,
        ], $orderable->register());
    }

    // 订单发货的时候调用
    public function expend()
    {
        // 查出所有变体数量 items
        return $this->orderable->items->map(function($item){
            $item->inventory = Warehouse::findVariant($item->variant_id,$item->quantity);
            return $item;
        });
        // 生成 inventory expend 记录

    }
}
