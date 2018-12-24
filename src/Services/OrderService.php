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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function model(Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function show(Order $order)
    {
        $this->order = $order;
        $this->order->loadMissing(['orderable.items.variant','market.marketable'])->append('simple_address');
        return $this->order;
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
        return $this->order;
    }

    // 预备出库
    public function createExpends(Request $request)
    {
        // 创建出库记录
        return InventoryExpendService::create($this->order);
    }

    // 出库
    public function take(Request $request)
    {

    }

    // 发货
    public function shipment(Request $request)
    {
        // 为InventoryExpendItemUnit 创建 Shipment 物流
        $inventoryExpendService = new InventoryExpendService($this->order->inventoryExpend);
        $inventoryExpendService->shipment($request);
        // 为InventoryExpendItemUnit 创建 Attachment 附加费用(包装材料费\人工费....)

        // 减少库存
        $this->take($request);
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