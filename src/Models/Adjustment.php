<?php

namespace Chang\Erp\Models;


use Chang\Erp\Traits\MoneyFormatableTrait;

class Adjustment extends Model
{
    use MoneyFormatableTrait;

    protected $connection = 'dealpaw';

    protected $table = 'adjustments';

    public function order()
    {
        return $this->belongsTo(DealpawOrder::class, 'order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(DealpawOrderItem::class, 'order_item_id');
    }

    public function getAmountAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $this->saveCurrencyUsing($value === 0 ? '0.00' : $value);
    }
}
