<?php

namespace Chang\Erp\Models;


class InventoryIncomeItemUnit extends Model
{
    protected $fillable = [
        'item_id',
        'shipment_track_id',
        'adjustments_total',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryIncomeItem::class, 'item_id');
    }

    public function shipment()
    {
        return $this->belongsTo(ShipmentTrack::class, 'shipment_track_id');
    }
}
