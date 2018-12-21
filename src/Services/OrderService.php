<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/6
 * Time: 3:08 PM
 */

namespace Chang\Erp\Services;


use App\Exceptions\InventoryOverflowException;
use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Models\DealpawOrder;
use Chang\Erp\Models\ExpendItem;
use Chang\Erp\Models\ExpendItems;
use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\Order;
use Chang\Erp\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // 同步全部订单
    public function syncAll()
    {
        return collect(Order::$orderables)->mapWithKeys(function ($orderable) {
            return app($orderable)->syncAll();
        });
    }

    // dp订单创建
    public function createByDp($id)
    {
        $this->order = DealpawOrder::syncOrder($id);
        return $this->preTake();
    }

    // 预备出库
    public function preTake()
    {
        // 创建出库记录
        return tap(InventoryService::expend($this->order),function(){
            // 🔐库存 直接 ProductVariant->stock 减少
            $this->order->getExpendItemList()->each->lock();
        });
    }

    // 出库
    public function take()
    {

    }

    // 发货
    public function shipment()
    {

    }

    // 关闭订单
    public function cancel()
    {
        // 还原🔐库存
    }

    public function rollback()
    {

    }
}