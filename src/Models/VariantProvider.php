<?php

namespace Chang\Erp\Models;


use Chang\Erp\Traits\MoneyFormatAble;

class VariantProvider extends Model
{
    use MoneyFormatAble;

    protected $connection = 'mysql';

    protected $fillable = [
        'product_variant_id',
        'product_provider_id',
        'price',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPriceAttribute($value)
    {
        return $this->displayCurrencyUsing($value);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $this->saveCurrencyUsing($value);
    }
}
