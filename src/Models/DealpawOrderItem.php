<?php

namespace Chang\Erp\Models;


use Chang\Erp\Traits\MoneyFormatableTrait;

class DealpawOrderItem extends Model
{
    use MoneyFormatableTrait;

    protected $connection = 'dealpaw';

    protected $table = 'order_items';

    protected $casts = [
        'option_values' => 'array',
    ];

    // dealpaw 订单
    public function order()
    {
        return $this->belongsTo(DealpawOrder::class, 'order_id');
    }

    // 关联变体
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // 关联产品
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // 关联调整
    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    // 最小单元
    public function units()
    {
        return $this->hasMany(DealpawOrderItemUnit::class, 'item_id');
    }

    public function getUnitsTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }


    public function getTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }


    public function getUnitPriceAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

}
