<?php

namespace Chang\Erp\Models;

use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Contracts\Incomeable;
use Chang\Erp\Traits\AddressableTrait;
use Chang\Erp\Traits\ExpendableTrait;
use Chang\Erp\Traits\IncomeableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Supplier
 * @property mixed admin
 * @package Chang\Erp\Models
 */
class Supplier extends Model implements HasMedia, Incomeable, Expendable
{
    use AddressableTrait, HasMediaTrait, IncomeableTrait, ExpendableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'level',
        'description',
    ];

    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
//        self::observe(ProductProviderObserver::class);
    }

    /**
     *
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('supplier_image');
    }

    /**
     * 官方运营人员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pensengUser()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 供应商账户 最多5个
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(SupplierUser::class, 'supplier_id');
    }

    /**
     * 供应商管理员
     * @return BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(SupplierUser::class, 'supplier_user_id')
            ->where('supplier_id', $this->id);
    }

    /**
     * 供应商变体
     */
    public function variants()
    {
//        $database = $this->getConnection()->getDatabaseName();
//        $dbName = config('database.connections.' . $database . '.database');
//        return $this->belongsToMany(ProductVariant::class, $dbName . '.supplier_variants')
//            ->withPivot('name')->wherePivot('hidden', 0);

        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany(ProductVariant::class,
            $database . '.supplier_variants')->using(SupplierVariant::class)
            ->withPivot('name')->wherePivot('hidden', 0);
    }


    public function products()
    {
        $dbName = config('database.connections.' . config('database.default') . '.database');
        return $this->belongsToMany(Product::class, $dbName . '.supplier_product');
    }


    public function getProductIdsAttribute()
    {
        return DB::table('supplier_product')->where('supplier_id', $this->id)->pluck('product_id');
    }

    public function getVariantIdsAttribute()
    {
        return DB::table('supplier_variants')->where('supplier_id', $this->id)->pluck('product_variant_id');
    }

    /**
     * 供应商办公室地址
     * @return mixed
     */
    public function officeAddress()
    {
        return $this->addressByCollection('office');
    }

    /**
     * 供应商仓库地址
     * @return mixed
     */
    public function warehouseAddress()
    {
        return $this->addressByCollection('warehouse');
    }

    public function getExpendItemList(): ExpendItems
    {
        // TODO: Implement getExpendItemList() method.
    }
}
