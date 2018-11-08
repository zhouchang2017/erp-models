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

    public function order()
    {
        return $this->belongsTo(DealpawOrder::class, 'order_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getUnitsTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setUnitsTotalAttribute($value)
    {
        $this->attributes['units_total'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : $value);
    }

    public function getTotalAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : $value);
    }

    public function getUnitPriceAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : $value);
    }
}
