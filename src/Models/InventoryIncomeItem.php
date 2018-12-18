<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Trackable;
use Chang\Erp\Observers\InventoryIncomeItemObserver;
use Chang\Erp\Traits\MoneyFormatableTrait;
use Chang\Erp\Traits\TrackableTrait;

/**
 * @property mixed id
 * @property mixed pcs
 */
class InventoryIncomeItem extends Model
{
    use MoneyFormatableTrait;

    protected $connection = 'mysql';

    protected $fillable = [
        'inventory_income_id',
        'product_id',
        'product_variant_id',
        'pcs',
        'price',
    ];

    protected $touches = ['inventoryIncome'];


    protected static function boot()
    {
        parent::boot();
        self::observe(InventoryIncomeItemObserver::class);
    }


    public function inventoryIncome()
    {
        return $this->belongsTo(InventoryIncome::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function units()
    {
        return $this->hasMany(InventoryIncomeItemUnit::class, 'item_id');
    }

    public function createItemUnits(array $attribute = [])
    {
        return $this->units()->createMany(
            array_fill(0, $this->pcs, $attribute)
        );
    }

    public function tracks()
    {
        return $this->belongsToMany(ShipmentTrack::class, 'inventory_income_item_units', 'item_id',
            'shipment_track_id');
    }
}
