<?php

namespace Chang\Erp\Models;

use Chang\Erp\Traits\MoneyFormatAble;

class ProductPrice extends Model
{
    use MoneyFormatAble;

    protected $connection = 'mysql';

    protected $table = 'product_variant_prices';

    protected $fillable = ['price'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
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
