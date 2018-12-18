<?php

namespace Chang\Erp\Models;



class ProductVariantOptionValueTranslation extends Model
{
    protected $connection = 'dealpaw';

    protected $fillable = ['value'];

    public $timestamps = false;
}
