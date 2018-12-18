<?php

namespace Chang\Erp\Models;

//use ProductProvider;
use Chang\Erp\Observers\ProductVariantObserver;
use Chang\Erp\Scopes\SupplierProductVariantScope;
use Dimsav\Translatable\Translatable;
use Illuminate\Support\Facades\App;

/**
 * @property mixed supplierVariant
 */
class ProductVariant extends Model
{
    use Translatable;

    protected $connection = 'dealpaw';

    public $translationForeignKey = 'translatable_id';

    public $translationModel = ProductVariantTranslation::class;

    public $translatedAttributes = ['name'];

    public static $searchableColumns = ['id', 'code'];

    protected $appends = ['variantName'];

    protected $with = ['price'];

    protected $hidden = ['position', 'shipping_category_id', 'sold'];

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

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierProductVariantScope());
        self::observe(ProductVariantObserver::class);
    }

    public function getVariantNameAttribute()
    {
        return $this->supplierVariant->name;
    }

    public function getName()
    {
        return optional($this->product)->name . '-' . implode('-', $this->optionValues->map->name->toArray());
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues()
    {
        return $this->hasMany(ProductVariantOptionValue::class, 'variant_id');
    }


    public function optionValuesGroupByOptionId()
    {
        return optional($this->optionValues, function ($optionValues) {
            return $optionValues->groupBy('option_id');
        });
    }


    public function prices()
    {
        return $this->hasMany(ProductVariantPrice::class, 'variant_id');
    }

    /**
     * 供应商定价
     */
    public function price()
    {
        return $this->hasOne(ProductPrice::class, 'variant_id', 'id')->latest();
    }


    public function supplier(): User
    {
        return $this->supplierVariant->suplier;
    }

    public function getSupplierAttribute()
    {
        return $this->supplier();
    }

    public function user(): User
    {
        return $this->supplierVariant->suplier;
    }

    public function getUserAttribute()
    {
        return $this->user();
    }

    public function supplierVariant()
    {
        return $this->hasOne(SupplierVariant::class, 'product_variant_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_variant_id');
    }

    public function hasPrice()
    {
        return $this->price()->count() > 0;
    }

//    public static function search($search)
//    {
//        return static::whereHas('translations', function ($query) use ($search) {
//            $query->where('locale_code', App::getLocale())
//                ->where('name', 'like', '%' . $search . '%');
//        });
//    }

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('translations', function ($query) use ($search) {
            $connectionType = $query->getModel()->getConnection()->getDriverName();

            $likeOperator = $connectionType == 'pgsql' ? 'ilike' : 'like';
            $query->where('locale_code', App::getLocale())
                ->where('name', $likeOperator, '%' . $search . '%');
        })->orWhere(function ($query) use ($search) {
            if (is_numeric($search) && in_array($query->getModel()->getKeyType(), ['int', 'integer'])) {
                $query->orWhere($query->getModel()->getQualifiedKeyName(), $search);
            }

            $model = $query->getModel();
            $connectionType = $query->getModel()->getConnection()->getDriverName();

            $likeOperator = $connectionType == 'pgsql' ? 'ilike' : 'like';

            foreach (static::$searchableColumns as $column) {
                $query->orWhere($model->qualifyColumn($column), $likeOperator, '%' . $search . '%');
            }
        });
    }

    protected function incrementStock($pcs)
    {
        $this->increment('stock', $pcs);
    }

}
