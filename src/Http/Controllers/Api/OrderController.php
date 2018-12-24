<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\Inventory;
use Chang\Erp\Models\Order;
use Chang\Erp\Services\InventoryService;
use Chang\Erp\Services\OrderService;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected $service;

    /**
     * OrderController constructor.
     * @param $service
     */
    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function show(Order $order)
    {
        return $this->service->show($order);
    }

    // 获取分配库存
    public function assignmentWarehouse(Order $order)
    {
       return Inventory::whereIn('product_variant_id',$order->orderable->items()->pluck('variant_id'))->with('warehouse')->get()->groupBy('product_variant_id');
    }

    public function storeExpend(Order $order,Request $request)
    {
        /*
         * data=>[
         *  ['warehouse_id'=>1,'product_variant_id'=>1,'pcs'=>1]
         * ]
         * */
    }

    public function syncAll()
    {
        $this->service->syncAll();
        return response()->json([
            'title' => '同步成功',
            'type' => 'success',], 201);
    }

    public function createByDp($id)
    {
        return response()->json(
            [
                'title' => '同步订单成功！',
                'data'=>$this->service->createByDp($id)
            ], 201);
    }
}
