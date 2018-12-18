<?php

namespace Chang\Erp\Models;


class InventoryExpendItemUnit extends Model
{
    protected $fillable = [
        'item_id',
        'shipment_track_id',
        'adjustments_total',
    ];

    protected $with = ['shipment'];

    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo(InventoryExpendItem::class, 'item_id');
    }

    public function shipment()
    {
        return $this->belongsTo(ShipmentTrack::class, 'shipment_track_id');
    }

}
