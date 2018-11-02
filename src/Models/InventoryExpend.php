<?php

namespace Chang\Erp\Models;


// 出库
use Chang\Erp\Contracts\Trackable;
use Chang\Erp\Traits\TrackableTrait;

class InventoryExpend extends Model implements Trackable
{
    use TrackableTrait;

    const UN_COMMIT = 0; //未提交
    const PADDING = 1;  //待审核
    const UN_SHIP = 2;  //代发货
    const SHIPPED = 3;  //已发货
    const COMPLETED = 4; //已完成

    protected $fillable = [
        'description',
        'pcs',
        'price',
        'status',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(InventoryExpendItem::class);
    }
}
