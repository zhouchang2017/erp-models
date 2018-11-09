<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Orderable;
use Chang\Erp\Traits\MoneyFormatableTrait;
use Chang\Erp\Traits\OrderableTrait;

/**
 * @property mixed dealpaw
 */
class DealpawOrder extends Model implements Orderable
{
    use MoneyFormatableTrait, OrderableTrait;

    protected $connection = 'dealpaw';

    protected $table = 'orders';

    protected $casts = [
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    public function address()
    {
        return $this->belongsTo(DealpawAddress::class, 'address_id');
    }

    public function dealpaw()
    {
        return $this->belongsTo(Dealpaw::class, 'channel_id');
    }

    public function items()
    {
        return $this->hasMany(DealpawOrderItem::class, 'order_id');
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class, 'order_id');
    }

    public function getItemsTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setItemsTotalAttribute($value)
    {
        $this->attributes['items_total'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : $value);
    }

    public function getTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : $value);
    }

    // 实现orderable

    public function getStatus(): string
    {
        // 订单取消
        if ($this->state === 'cancelled') {
            return Order::CANCEL;
        }
        // 订单已发货
        if ($this->shipping_state === 'shipped') {
            return Order::SHIPPED;
        }
        // 订单以付款
        if ($this->payment_state === 'paid') {
            return Order::UN_SHIPPED;
        }
        // 等待买家付款
        if ($this->state === 'new') {
            return Order::PENDING;
        }

        return Order::PENDING;
    }

    public function getMarketId()
    {
        return $this->dealpaw->market->id;
    }

    public function getTotalPrice(): string
    {
        return $this->total;
    }

    public function getExpendItemList(): ExpendItems
    {
        $data = $this
            ->items()
            ->get(['product_id', 'variant_id', 'quantity', 'units_total'])
            ->map(function ($order) {
                return [
                    'product_id' => $order->product_id,
                    'product_variant_id' => $order->variant_id,
                    'pcs' => $order->quantity,
                    'price' => $order->units_total,
                ];
            });
        return new ExpendItems($data);
    }
}
