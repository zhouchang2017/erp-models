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
    /**
     * 计算数量
     * @return mixed
     */
    public function calcTotalPcs()
    {
        return $this->items()->sum('pcs');
    }

    /**
     * 计算价格
     * @return mixed
     */
    public function calcTotalPrice()
    {
        return $this->items()->sum('price');
    }

    /**
     * 计算items 价格|数量
     */
    public function autoCalcItems()
    {
        $this->price = $this->calcTotalPrice();
        $this->pcs = $this->calcTotalPcs();
        return $this;
    }

    /**
     * 更新审核时间
     */
    public function updateConfirmedAt()
    {
        if (is_null($this->confirmed_at)) {
            $this->confirmed_at = now();
        }
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