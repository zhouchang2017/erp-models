<?php

/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 6:02 PM
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Warehouse;

trait UpdateInventoryTrait
{
    public function calcTotalPcs()
    {
        return $this->items()->sum('pcs');
    }

    public function calcTotalPrice()
    {
        return $this->items()->sum('price');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function setStatusAttribute($value)
    {
        if ((int)$value === self::PADDING) {
            $this->attributes['status'] = $this->warehouse->has_verify ? $value : self::UN_SHIP;
        } else {
            $this->attributes['status'] = $value;
        }
    }
}