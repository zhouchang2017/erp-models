<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\InventoryIncome;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class InventoryIncomeController extends Controller
{

    public function show(InventoryIncome $income)
    {
        $income->loadMissing(['items.variant', 'warehouse', 'items.units']);
        return $income;
    }


    /**
     * 供应商提交审核
     * @param InventoryIncome $income
     * @return \Illuminate\Http\JsonResponse
     */
    public function review(InventoryIncome $income)
    {
        $income->statusToPadding();

        return response()->json([
            'status' => $income->refresh()->status,
            'title' => '提交成功',
            'message' => '请耐心等待平台审核',
            'type' => 'success',
        ], 201);
    }

    public function approved(InventoryIncome $income)
    {
        $income->statusToConfirmed();

        return response()->json([
            'status' => $income->refresh()->status,
            'title' => '审核通过',
            'message' => '审核已通过，等待供应商发货',
            'type' => 'success',
        ], 201);
    }


}
