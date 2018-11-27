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
use Illuminate\Database\Eloquent\Collection;

/**
 * @property Collection items
 * @property Warehouse warehouse
 */
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

    public static function statusStepOptions()
    {
        return [
            ['title' => '未提交', 'description' => '请完善入库信息', 'value' => self::UN_COMMIT],
            ['title' => '审核中', 'description' => '等待工作人员审核您的入库信息', 'value' => self::PADDING],
            ['title' => '等待发货', 'description' => '审核已通过，等待发货', 'value' => self::UN_SHIP],
            ['title' => '等待平台收货', 'description' => '发货已完成，等待平台接收货品', 'value' => self::SHIPPED],
            ['title' => '已完成', 'description' => '商品已入库', 'value' => self::COMPLETED],
        ];
    }

    protected $fillable = [
        'description',
        'pcs',
        'price',
        'status',
        'warehouse_id',
        'has_shipment',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime', // 审核通过时间
        'shipped_at' => 'datetime',   // $this->needlessShipment() 无需物流
        'completed_at' => 'datetime',
        'has_shipment' => 'boolean',
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


    public function beforeCompleted()
    {
        Inventory::put($this);
    }

}
