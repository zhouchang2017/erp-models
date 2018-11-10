<?php

namespace Chang\Erp\Http\Controllers\Api;

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

    public function syncAll()
    {
        $this->service->syncAll();
        return response()->json(['message' => '同步完成'], 201);
    }
}
