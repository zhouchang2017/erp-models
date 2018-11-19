<?php

namespace Chang\Erp\Models;


use Chang\Erp\Observers\InventoryIncomeItemObserver;
use Chang\Erp\Traits\MoneyFormatableTrait;

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
//        self::observe(InventoryIncomeItemObserver::class);
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
}
