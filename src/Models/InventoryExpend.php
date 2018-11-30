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
use Spatie\ModelStatus\HasStatuses;

/**
 * @property mixed items
 */
class InventoryExpend extends Model implements Trackable, Commentable
{
    use TrackableTrait,
        CommentableTrait,
        MoneyFormatableTrait,
        UpdateInventoryTrait,
        HasStatuses;

    const UN_COMMIT = 'UN_COMMIT'; //未提交
    const PADDING = 'PADDING';  //待审核
    const UN_SHIP = 'UN_SHIP';  //代发货
    const SHIPPED = 'SHIPPED';  //已发货
    const COMPLETED = 'COMPLETED'; //已完成
    const CANCEL = 'CANCEL'; // 取消

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
            self::CANCEL => '取消',
        ];
    }

    /*
     * 状态改变验证
     * */
    public function isValidStatus(string $name, ?string $reason = null): bool
    {
        return $this->status !== $name;
    }


    public function expendable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(InventoryExpendItem::class);
    }


    // 出货清单审核通过后置操作
    protected function afterConfirmed()
    {
        Inventory::take($this);
    }

    // 出货单取消前置钩子
    protected function beforeCancel()
    {
        // 当前出货单状态为审核通过之后状态，取消则，返仓
        if ((int)$this->attributes['status'] >= self::UN_SHIP) {
            Inventory::rollback($this);
        }
    }

    public function reExpend()
    {
        return tap($this, function (self $instance) {
            // 重置出库
            $instance->beforeCancel();
            // 初始化状态
            $instance->statusToSave();
        });
    }

}
