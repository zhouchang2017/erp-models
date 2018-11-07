<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Addressable;
use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Traits\AddressableTrait;
use Chang\Erp\Traits\ExpendableTrait;

class Warehouse extends Model implements Addressable, Expendable
{
    use AddressableTrait, ExpendableTrait;

    protected $fillable = ['name', 'type_id'];

    protected $fieldSearchable = [
        'id',
        'name',
        'enabled',
        'has_verify',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'has_verify' => 'boolean',
    ];

    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

//        self::observe(WarehouseObserver::class);
    }


    public function type()
    {
        return $this->belongsTo(WarehouseType::class, 'type_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function variants($name = null)
    {
//        return $this->hasManyThrough(
//            ProductVariant::class,
//            Inventory::class,
//            'warehouse_id',
//            'id',
//            'id',
//            'product_variant_id'
//        );
        return $this->inventories()->with([
            'supplier',
            'variant',
        ]);
    }

    public function getVariantsAttribute()
    {
        return $this->variants()->get();
    }

}
