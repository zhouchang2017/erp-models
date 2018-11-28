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
        $income->loadMissing(['items.variant','warehouse']);
        return $income;
    }

    public function review(InventoryIncome $income)
    {
        $income->statusToPadding();
        return response()->json([
            'status' => $income->status,
            'title' => '提交成功',
            'message' => '请耐心等待平台审核',
            'type' => 'success',
        ], 201);
    }
}
