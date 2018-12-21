<?php

namespace Chang\Erp\Http\Controllers\Rpc;

use Chang\Erp\Services\OrderService;

class Order
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

    public function syncAll()
    {
        $this->service->syncAll();
        return response()->json(['message' => '同步完成'], 201);
    }


    public function createByDp($id)
    {
        $this->service->createByDp($id);
        return response()->json(['message' => '同步订单成功！'], 201);
    }
}
