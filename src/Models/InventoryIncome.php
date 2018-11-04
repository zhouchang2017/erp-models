<?php

namespace Chang\Erp\Models;


// 入库
use Chang\Erp\Contracts\Commentable;
use Chang\Erp\Contracts\Trackable;
use Chang\Erp\Observers\InventoryIncomeObserver;
use Chang\Erp\Traits\CommentableTrait;
use Chang\Erp\Traits\UpdateInventoryTrait;
use Chang\Erp\Traits\MoneyFormatableTrait;
use Chang\Erp\Traits\TrackableTrait;

class InventoryIncome extends Model implements Trackable, Commentable
{
    use TrackableTrait,
        MoneyFormatableTrait,
        UpdateInventoryTrait,
        CommentableTrait;

    const UN_COMMIT = 0; //未提交
    const PADDING = 1;  //待审核
    const UN_SHIP = 2;  //代发货
    const SHIPPED = 3;  //已发货
    const COMPLETED = 4; //已完成

    public static function selectOptions()
    {
        return [
            self::UN_COMMIT => '保存',
            self::PADDING => '提交',
            self::UN_SHIP => '等待发货',
            self::SHIPPED => '已发货',
            self::COMPLETED => '已完成',
        ];
    }

    protected $fillable = [
        'description',
        'pcs',
        'price',
        'status',
        'warehouse_id',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        self::observe(InventoryIncomeObserver::class);
    }


    public function incomeable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(InventoryIncomeItem::class);
    }

}
