<?php

namespace Chang\Erp\Models;

use App\Exceptions\InventoryOverflowException;
use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Contracts\Orderable;
use Chang\Erp\Traits\ExpendableTrait;
use Chang\Erp\Traits\MoneyFormatableTrait;
use Illuminate\Support\Collection;

/**
 * Class Order
 * @property mixed inventoryExpends
 * @property mixed orderable
 * @package Chang\Erp\Models
 */
class Order extends Model implements Expendable
{
    use MoneyFormatableTrait, ExpendableTrait;

    const PENDING = 0;              //等待买家付款
    const UN_SHIPPED = 1;            //买家已付款，等待卖家发货
    const PARTIALLY_SHIPPED = 2;    //部分发货
    const SHIPPED = 3;              //已发货
    const CANCEL = 4;               //订单已取消
    const UNFULFILLABLE = 5;       // 订单无法进行配送

    public $timestamps = false;

    protected $fillable = [
        'order_status',
        'market_id',
        'price',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static $orderables = [
        DealpawOrder::class,
    ];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public static function syncOrder(Orderable $orderable)
    {
        return static::updateOrCreate([
            'orderable_type' => get_class($orderable),
            'orderable_id' => $orderable->id,
        ], $orderable->register());
    }

    public function getDescription(): string
    {
        return $this->market()->pluck('name')->first() . '-' . $this->orderable->number;
    }

    public function address()
    {
        return $this->orderable->address();
    }

    /**
     * 取消出货单
     * @return mixed
     */
    public function cancelExpend()
    {
        return $this->inventoryExpends->map->statusToCancel();
    }

    /*
     * 重置出货单
     * */
    public function reExpend()
    {
        $this->inventoryExpends->map->reExpend();
    }

    public function getExpendItemList(): ExpendItems
    {
        return $this->orderable->getExpendItemList();
    }
}
