<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Addressable;
use Chang\Erp\Traits\AddressableTrait;

class Warehouse extends Model implements Addressable
{
    use AddressableTrait;

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

    public function variants()
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
            'variant'
        ]);
    }

    public function getVariantsAttribute()
    {
        return $this->variants()->get();
    }

}
