<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Http\Resources\InventoryAbleResource;
use Chang\Erp\Models\InventoryIncome;
use Chang\Erp\Models\InventoryIncomeItemUnit;
use Chang\Erp\Services\InventoryIncomeService;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class InventoryIncomeController extends Controller
{

    protected $service;

    public function __construct(InventoryIncomeService $service)
    {
        $this->service = $service;
    }

    public function show(InventoryIncome $income)
    {
        return new InventoryAbleResource($income);
    }


    /**
     * 供应商提交审核
     * @param InventoryIncome $income
     * @return \Illuminate\Http\JsonResponse
     */
    public function review(InventoryIncome $income)
    {
        $this->service->model($income)->statusToPending();
        return response()->json([
            'status' => $income->refresh()->status,
            'title' => '提交成功',
            'message' => '请耐心等待平台审核',
            'type' => 'success',
        ], 201);
    }

    public function approved(InventoryIncome $income)
    {
        $this->service->model($income)->statusToApproved();
        return response()->json([
            'status' => $income->refresh()->status,
            'title' => '审核通过',
            'message' => '审核已通过，等待供应商发货',
            'type' => 'success',
        ], 201);
    }

    public function shipment(InventoryIncome $income, Request $request)
    {
        $res = $this->service->model($income)->shipment($request);
        return response()->json([
            'data' => $res,
            'title' => '发货成功',
            'message' => '发货成功！',
            'type' => 'success',
        ], 201);
    }

    public function put(InventoryIncome $income)
    {
        $res = $this->service->model($income)->put();
        return response()->json([
            //'data' => $res,
            'status' => $income->refresh()->status,
            'title' => '入库成功',
            'message' => '入库成功！',
            'type' => 'success',
        ], 201);
    }


}
