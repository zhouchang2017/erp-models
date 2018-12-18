<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Http\Resources\InventoryAbleResource;
use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\InventoryIncome;
use Chang\Erp\Services\InventoryExpendService;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class InventoryExpendController extends Controller
{

    protected $service;

    public function __construct(InventoryExpendService $service)
    {
        $this->service = $service;
    }

    public function show(InventoryExpend $expend)
    {
        return new InventoryAbleResource($expend);
    }


    /**
     * 供应商提交审核
     * @param InventoryExpend $expend
     * @return \Illuminate\Http\JsonResponse
     */
    public function review(InventoryExpend $expend)
    {
        $this->service->model($expend)->statusToPending();
        return response()->json([
            'status' => $expend->refresh()->status,
            'title' => '提交成功',
            'message' => '请耐心等待平台审核',
            'type' => 'success',
        ], 201);
    }

    public function approved(InventoryExpend $expend)
    {
        $this->service->model($expend)->statusToApproved();
        return response()->json([
            'status' => $expend->refresh()->status,
            'title' => '审核通过',
            'message' => '审核已通过，等待平台发货',
            'type' => 'success',
        ], 201);
    }

    public function shipment(InventoryExpend $expend, Request $request)
    {
        $res = $this->service->model($expend)->shipment($request);
        return response()->json([
            'data' => $res,
            'title' => '发货成功',
            'message' => '发货成功！',
            'type' => 'success',
        ], 201);
    }

    public function take(InventoryExpend $expend)
    {
        $this->service->model($expend)->take();
        return response()->json([
            'data' => $expend->refresh()->status,
            'title' => '退仓成功',
            'message' => '退仓已完成！',
            'type' => 'success',
        ], 201);
    }

    public function cancel(InventoryExpend $expend)
    {
        $this->service->model($expend)->cancel();
        return response()->json([
            'data' => $expend->refresh()->status,
            'title' => '退仓成功',
            'message' => '退仓已完成！',
            'type' => 'success',
        ], 201);
    }


}
