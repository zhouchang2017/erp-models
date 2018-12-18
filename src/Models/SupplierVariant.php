<?php

namespace Chang\Erp\Models;

use Chang\Erp\Observers\SupplierVariantObserver;
use Chang\Erp\Traits\MoneyFormatableTrait;

class SupplierVariant extends Model
{
    use MoneyFormatableTrait;

    protected $connection = 'mysql';

    protected $fillable = [
        'product_variant_id',
        'supplier_id',
        'price',
    ];

    protected static function boot()
    {
        parent::boot();
        self::observe(SupplierVariantObserver::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_variant_id', 'product_variant_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

}
