<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\InventoryIncome;
use Chang\Erp\Models\ProductVariant;
use Chang\Erp\Models\Warehouse;
use Chang\Erp\Services\ShipmentService;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class WarehouseController extends Controller
{
    public function variants(Warehouse $warehouse,Request $request)
    {
        return $warehouse->variants()->search($request->search)->latest()->paginate();
    }
}
