<?php

namespace Chang\Erp\Http\Resources;

use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\InventoryIncome;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class InventoryAbleResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof InventoryIncome) {
            return $this->inventoryIncomeToArray($request);
        }
        if ($this->resource instanceof InventoryExpend) {
            return $this->inventoryExpendToArray($request);
        }
    }

    protected function inventoryIncomeToArray($request)
    {
        $this->loadMissing(['items.variant', 'items.units', 'warehouse']);
        return [
            'id' => $this->id,
            'description' => $this->description,
            'pcs' => $this->pcs,
            'incomeable_type' => $this->incomeable_type,
            'incomeable_id' => $this->incomeable_id,
            'warehouse_id' => $this->warehouse_id,
            'has_shipment' => $this->has_shipment,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'items' => $this->items,
            'shipment' => [
                'name' => $this->warehouse->name,
                'simple_address' => $this->warehouse->simple_address,
            ],
            'status'=>$this->status
        ];
    }

    protected function inventoryExpendToArray($request)
    {
        $this->loadMissing(['items.variant', 'items.units','expendable']);
        return [
            'id' => $this->id,
            'description' => $this->description,
            'pcs' => $this->pcs,
            'expendable_type' => $this->expendable_type,
            'expendable_id' => $this->expendable_id,
            'warehouse_id' => $this->warehouse_id,
            'has_shipment' => $this->has_shipment,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'items' => $this->items,
            'shipment' => [
                'name' => $this->expendable->name,
                'simple_address' => $this->expendable->simple_address,
            ],
            'status'=>$this->status
        ];
    }
}
