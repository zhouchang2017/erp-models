<?php

namespace Chang\Erp\Models;


// 出库
use Chang\Erp\Contracts\Commentable;
use Chang\Erp\Contracts\Trackable;
use Chang\Erp\Observers\InventoryExpendObserver;
use Chang\Erp\Traits\CommentableTrait;
use Chang\Erp\Traits\UpdateInventoryTrait;
use Chang\Erp\Traits\MoneyFormatableTrait;
use Chang\Erp\Traits\TrackableTrait;

/**
 * @property mixed items
 */
class InventoryExpend extends Model implements Trackable, Commentable
{
    use TrackableTrait,
        CommentableTrait,
        MoneyFormatableTrait,
        UpdateInventoryTrait;

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
        'warehouse_id',
        'has_shipment',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'has_shipment' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        self::observe(InventoryExpendObserver::class);
    }


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

    public function expendable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(InventoryExpendItem::class);
    }

    public function afterShipped()
    {
        //
    }

    public function afterCompleted()
    {
        //
    }

    public function beforeShipped()
    {
        Inventory::take($this);
    }

    public function beforeCompleted()
    {
        //
    }
}
