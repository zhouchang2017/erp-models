<?php

namespace Chang\Erp\Models;


use Chang\Erp\Observers\InventoryExpendItemObserver;
use Chang\Erp\Traits\MoneyFormatableTrait;

class InventoryExpendItem extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = [
        'inventory_expend_id',
        'product_id',
        'product_variant_id',
        'pcs',
        'price',
    ];

    protected $touches = ['inventoryExpend'];

    protected static function boot()
    {
        parent::boot();
        self::observe(InventoryExpendItemObserver::class);
    }


    public function inventoryExpend()
    {
        return $this->belongsTo(InventoryExpend::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }


}
