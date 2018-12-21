<?php

namespace Chang\Erp\Models;

class DealpawOrderItemUnit extends Model
{
    protected $connection = 'dealpaw';

    protected $table = 'order_item_units';

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function getTotalAttribute()
    {
        // TODO item.unit_price + adjustment_total
    }
}