<?php

namespace Chang\Erp\Models;

use Chang\Erp\Observers\ProductObserver;
use Chang\Erp\Scopes\SupplierProductScope;
use Chang\Erp\Traits\ProductCheckStatus;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Dimsav\Translatable\Translatable;


class Product extends Model implements HasMedia
{
    use Translatable, HasMediaTrait, ProductCheckStatus;

    protected $connection = 'dealpaw';

    const UN_COMMIT = 'saved';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const POSTPONED = 'postponed';

    public static function canToReviewValue()
    {
        return self::UN_COMMIT;
    }

    public static function statusStepOptions()
    {
        return [
            ['title' => '未提交', 'description' => '编辑产品信息', 'value' => self::UN_COMMIT],
            ['title' => '审核中', 'description' => '等待工作人员审核您的产品信息', 'value' => self::PENDING],
            ['title' => '审核通过', 'description' => '审核已通过，产品已经进入产品库', 'value' => self::APPROVED],
        ];
    }

    public $translationForeignKey = 'translatable_id';

    public $translationModel = ProductTranslation::class;

    public $translatedAttributes = [
        'name',
        'short_description',
        'description',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    protected $fillable = ['code', 'taxon_id', 'enabled'];

    protected static function boot()
    {
        parent::boot();
        self::observe(ProductObserver::class);
        static::addGlobalScope(new SupplierProductScope());
    }


    public function registerMediaCollections()
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('product_image');
    }


    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_related_product', 'product_id', 'related_id');
    }


    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(ProductAttribute::class, 'product_attribute_values', 'product_id', 'attribute_id')
//            ->wherePivot('locale_code', Context::channel()->locale_code)
            ->withPivot('id', 'locale_code', 'text_value', 'boolean_value', 'float_value', 'datetime_value',
                'json_value')
            ->as('value')
            ->using(ProductAttributeValue::class);
    }

    public function getAttributeValuesTranslationAttribute()
    {
        return $this->attributeValues->groupBy('attribute_id')
            ->map(function ($attr) {
                return $attr->groupBy('locale_code')->map(function ($prop) {
                    return $prop->first();
                });
            });
    }


    public function options()
    {
        return $this->belongsToMany(ProductOption::class, 'product_product_option', 'product_id', 'option_id');
    }

    public function optionValues()
    {
        return $this->hasManyThrough(
            ProductVariantOptionValue::class,
            ProductVariant::class,
            'product_id',
            'variant_id'
        );
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variant()
    {
        return $this->hasOne(ProductVariant::class)->groupBy('product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function image()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->groupBy('product_id');
    }

    public function officialVideo()
    {
        return $this->video()->ofType(0);
    }

    public function kolVideos()
    {
        return $this->videos()->ofType(1);
    }

    /**
     * 通过loadMissing加载
     *
     * @return mixed
     */
    public function video()
    {
        $query = $this->hasOne(Video::class)->groupBy('product_id');

        return $query;
    }

    public function videos()
    {
        $query = $this->hasMany(Video::class);

        return $query;
    }

    public function taxon()
    {
        return $this->belongsTo(Taxon::class);
    }

    public function taxons()
    {
        return $this->belongsToMany(Taxon::class, 'product_taxon');
    }

    public function scopeBySupplier($query, Supplier $supplier)
    {
        return $query->whereIn('id', $supplier->products()->pluck('id'));
    }

    public function supplier()
    {
        $dbName = config('database.connections.' . config('database.default') . '.database');
        return $this->belongsToMany(Supplier::class, $dbName . '.supplier_product');
    }
    
    

}
