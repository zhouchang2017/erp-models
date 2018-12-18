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

    const UN_COMMIT = 'UN_COMMIT'; //未提交(保存)
    const PENDING = 'PENDING';  //待审核(提交审核)
    const UN_SHIP = 'UN_SHIP';  //待发货
    const PART_SHIPPED = 'PART_SHIPPED';  //部分发货
    const SHIPPED = 'SHIPPED';  //已发货
    const COMPLETED = 'COMPLETED'; //已完成
    const CANCEL = 'CANCEL'; //取消

    protected $fillable = [
        'description',
        'pcs',
        'price',
        'warehouse_id',
        'has_shipment',
    ];

    protected $casts = [
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
            self::PENDING => '提交',
            self::UN_SHIP => '等待发货',
            self::SHIPPED => '已发货',
            self::COMPLETED => '已完成',
            self::CANCEL => '取消',
        ];
    }

    public static function canToReviewValue()
    {
        return self::UN_COMMIT;
    }

    public static function statusStepOptions()
    {
        return [
            ['title' => '未提交', 'description' => '请完善退仓信息', 'value' => self::UN_COMMIT],
            ['title' => '审核中', 'description' => '等待工作人员审核您的退仓申请', 'value' => self::PENDING],
            ['title' => '等待发货', 'description' => '审核已通过，等待平台发货', 'value' => self::UN_SHIP],
            ['title' => '部分发货', 'description' => '部分商品已发货', 'value' => self::PART_SHIPPED],
            ['title' => '全部发货', 'description' => '发货已完成', 'value' => self::SHIPPED],
            ['title' => '已完成', 'description' => '退仓已完成', 'value' => self::COMPLETED],
        ];
    }

    public static function failOption()
    {
        return ['title' => '已取消', 'description' => '退仓申请已取消', 'value' => self::CANCEL];
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

    /*
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     * */
    public function units()
    {
        return $this->hasManyThrough(
            InventoryExpendItemUnit::class,
            InventoryExpendItem::class,
            'inventory_expend_id',
            'item_id'
        );
    }

}
