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
        'product_provider_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function getSupplierId()
    {
        return $this->supplier->supplier->id;
    }
}
