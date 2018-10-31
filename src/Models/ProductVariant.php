<?php

namespace Chang\Erp\Models;

use App\Modules\ProductProvider\Models\ProductProvider;
use App\Modules\ProductProvider\Models\VariantProvider;
use Dimsav\Translatable\Translatable;

class ProductVariant extends Model
{
    use Translatable;

    protected $connection = 'dealpaw';

    public $translationForeignKey = 'translatable_id';

    public $translationModel = ProductVariantTranslation::class;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'product_id',
        'code',
        'position',
        'width',
        'height',
        'length',
        'weight',
        'stock',
        'shipping_category_id',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues()
    {
        return $this->belongsToMany(
            ProductOptionValue::class,
            'product_variant_option_value',
            'variant_id',
            'option_value_id'
        );
    }

    public function prices()
    {
        return $this->hasMany(ProductVariantPrice::class, 'variant_id');
    }

    public function price()
    {
        return $this->hasOne(ProductPrice::class, 'variant_id', 'id');
    }


    public function provider()
    {
        return $this->hasOne(VariantProvider::class);
    }

    public function hasPrice()
    {
        return $this->price()->count() > 0;
    }

}
