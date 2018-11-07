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
            return new InventoryAbleResource(app($this->model[$type])->with('items.variant', 'to.address', 'tracks')
                ->find($resourceId));
        }
    }

    public function shipment($type, $resourceId, Request $request)
    {
        if (array_key_exists($type, $this->model)) {
            $service = new ShipmentService($this->getModel($type, $resourceId));
            $service->fillAttributeFromRequest($request);
        }
    }

    public function completed($type, $resourceId)
    {
        return $this->getModel($type, $resourceId)->statusToCompleted();
    }

}
