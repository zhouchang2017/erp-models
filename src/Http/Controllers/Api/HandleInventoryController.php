<?php

namespace Chang\Erp\Http\Controllers\Api;

use App\Http\Resources\InventoryAbleResource;
use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\InventoryIncome;
use Chang\Erp\Services\ShipmentService;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class HandleInventoryController extends Controller
{
    protected $model = [
        'inventory-incomes' => InventoryIncome::class,
        'inventory-expends' => InventoryExpend::class,
    ];

    protected function getModel($type, $resourceId)
    {
        return app($this->model[$type])->findOrFail($resourceId);
    }

    public function show($type, $resourceId)
    {
        if (array_key_exists($type, $this->model)) {
            $resource = app($this->model[$type])->findOrFail($resourceId);
            return new InventoryAbleResource($resource);
        }
        return response('not fount', 404);
    }

    public function shipment($type, $resourceId, Request $request)
    {
        if (array_key_exists($type, $this->model)) {
            $service = new ShipmentService($this->getModel($type, $resourceId));
            $service->shipment($request);
            return response('', 201);
        }
        return response('not fount', 404);
    }

    public function completed($type, $resourceId)
    {
        if (array_key_exists($type, $this->model)) {
            $service = new ShipmentService($this->getModel($type, $resourceId));
            $service->completed();
            return response('', 201);
        }
        return response('not fount', 404);
    }


}
