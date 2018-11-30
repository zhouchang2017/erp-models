<?php

namespace Chang\Erp\Models;


class ProductVariantOptionValue extends Model
{
    protected $connection = 'dealpaw';

    protected $fillable = ['option_id', 'variant_id', 'value', 'locale_code'];
}
