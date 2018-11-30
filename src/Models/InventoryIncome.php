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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\ModelStatus\HasStatuses;

/**
 * @property Collection items
 * @property Warehouse warehouse
 */
class InventoryIncome extends Model implements Trackable, Commentable
{
    use TrackableTrait,
        MoneyFormatableTrait,
        UpdateInventoryTrait,
        CommentableTrait,
        HasStatuses;

    const UN_COMMIT = 'UN_COMMIT'; //未提交(保存)
    const PADDING = 'PADDING';  //待审核
    const UN_SHIP = 'UN_SHIP';  //代发货
    const SHIPPED = 'SHIPPED';  //已发货
    const COMPLETED = 'COMPLETED'; //已完成
    const CANCEL = 'CANCEL'; //取消


    public static function selectOptions()
    {
        return [
            self::UN_COMMIT => '未提交',
            self::PADDING => '待审核',
            self::UN_SHIP => '代发货',
            self::SHIPPED => '途中',
            self::COMPLETED => '已完成',
        ];
    }

    public static function filterOptions()
    {
        return [
            '未提交' => (string)self::UN_COMMIT,
            '待审核' => (string)self::PADDING,
            '代发货' => (string)self::UN_SHIP,
            '途中' => (string)self::SHIPPED,
            '已完成' => (string)self::COMPLETED,
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
        'warehouse_id',
        'has_shipment',
    ];

    protected $casts = [
        'has_shipment' => 'boolean',
    ];

    protected $with = ['statuses'];

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

    public function createItemUnits()
    {

    }

}
